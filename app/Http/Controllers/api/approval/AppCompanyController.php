<?php

namespace App\Http\Controllers\api\approval;

use App\Http\Controllers\Controller;
use App\Models\Master\CompanyModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\Master\Actor;

class AppCompanyController extends Controller
{
    public function getTableName()
    {
        return "company";
    }

    public function getData()
    {
        DB::enableQueryLog();
        $data['data'] = [];
        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $datadb = DB::table('company as m')
            ->leftJoin(DB::raw('
            (SELECT remarks,
                    GROUP_CONCAT(id SEPARATOR ", ") as company_file_ids,
                    GROUP_CONCAT(file SEPARATOR ", ") as company_file_names,
                    GROUP_CONCAT(file_path SEPARATOR ", ") as company_file_paths
             FROM company_file
             GROUP BY remarks
            ) as cf
        '), 'm.nama_company', '=', 'cf.remarks')
            ->select('m.*', 'cf.company_file_ids', 'cf.company_file_names', 'cf.company_file_paths')
            ->whereIn('m.type', ['NOTARIS', 'FINANCE'])
            ->whereNull('m.deleted')
            ->orderBy('m.id', 'desc');

        // dd($datadb->get()->toArray());

        if (isset($_POST)) {
            $data['recordsTotal'] = $datadb->get()->count();
            if (isset($_POST['search']['value'])) {
                $keyword = $_POST['search']['value'];
                $datadb->where(function ($query) use ($keyword) {
                    $query->where('m.nama_company', 'LIKE', '%' . $keyword . '%');
                    $query->orWhere('m.alamat', 'LIKE', '%' . $keyword . '%');
                    $query->orWhere('m.type', 'LIKE', '%' . $keyword . '%');
                    $query->orWhere('cf.remarks', 'LIKE', '%' . $keyword . '%');
                });
            }

            if (isset($_POST['order'][0]['column'])) {
                $datadb->orderBy('m.id', $_POST['order'][0]['dir']);
            }

            $data['recordsFiltered'] = $datadb->get()->count();

            if (isset($_POST['length'])) {
                $datadb->limit($_POST['length']);
            }
            if (isset($_POST['start'])) {
                $datadb->offset($_POST['start']);
            }
        }

        $data['data'] = $datadb->get()->toArray();
        $data['draw'] = $_POST['draw'];

        $query = DB::getQueryLog();
        // echo '<pre>';
        // print_r($query);die;
        return json_encode($data);
    }

    public function submit(Request $request)
    {
        $data = $request->all();
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            $decryptedUserId = Crypt::decrypt($data['user_id']);
            $action = $data['id'] == '' ? new CompanyModel() : CompanyModel::find($data['id']);
            $action->status = 'APPROVED';
            $action->approved_by = $decryptedUserId;
            $action->approved_date = now();
            $action->save();

            $actorAction = new Actor();
            $actorAction->users = $decryptedUserId;
            $actorAction->content = 'Akun Perusahaan ' . $data['company'] . ' Telah di Verified oleh ' . $data['name'];
            $actorAction->action = 'Perusahaan ' . $data['company'] . ' Telah di Verified';
            $actorAction->created_at = now();
            $actorAction->save();

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $result['message'] = $th->getMessage();
        }

        try {
            $phoneNumber = '+62' . ltrim($data['telp'], '0');
            $message = "Perusahaan Anda, {$data['company']} telah di Verified.";
            sendFonteNotification($phoneNumber, $message);
        } catch (\Throwable $notificationError) {
            \Log::error('Gagal mengirim notifikasi: ' . $notificationError->getMessage());
        }

        return response()->json($result);
    }

    public function confirmReject(Request $request)
    {
        $data = $request->all();
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            $decryptedUserId = Crypt::decrypt($data['user_id']);

            $action = CompanyModel::find($data['id']);
            $action->status = 'REJECTED';
            $action->approved_by = $decryptedUserId;
            $action->remarks = $data['remarks'];
            $action->save();

            $actorAction = new Actor();
            $actorAction->users = $decryptedUserId;
            $actorAction->content = 'Akun Perusahaan ' . $data['company'] . ' Telah di Tolak oleh ' . $data['name'];
            $actorAction->action = 'Perusahaan ' . $data['company'] . ' Telah di Tolak';
            $actorAction->created_at = now();
            $actorAction->save();

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }

        try {
            $phoneNumber = '+62' . ltrim($data['telp'], '0');
            $message = "Perusahaan Anda, {$data['company']} telah di tolak, karena {$data['remarks']}. Jika ada pertanyaan silahkan kontak customer service kami, Terima Kasih.";
            sendFonteNotification($phoneNumber, $message);
        } catch (\Throwable $notificationError) {
            \Log::error('Gagal mengirim notifikasi: ' . $notificationError->getMessage());
        }

        return response()->json($result);
    }

    public function delete(Request $request)
    {
        $data = $request->all();
        return view('web.approval_company.modal.confirmdelete', $data);
    }
}
