<?php

namespace App\Http\Controllers\api\approval;

use App\Http\Controllers\Controller;
use App\Models\Master\Karyawan;
use App\Models\Master\Users;
use App\Models\Master\CompanyModel;
use App\Models\Master\Branch;
use Illuminate\Http\Request;
use App\Models\Master\Actor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Imports\KaryawanImport;

class AppKaryawanController extends Controller
{
    public function getTableName()
    {
        return "karyawan";
    }

    public function getData()
    {
        DB::enableQueryLog();
        $data['data'] = [];
        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $company = session('id_company');
        $akses = session('akses');
        $datadb = DB::table($this->getTableName() . ' as m')
            ->select([
                'm.*',
                'u.nama_company'
            ])
            ->join('company as u', 'u.id', 'm.company')
            ->whereNull('m.deleted')
            ->orderBy('m.id', 'desc');

        // if (strtolower($akses) != 'superadmin') {
        //     $datadb->where('u.id', $company);
        // }

        if (isset($_POST)) {
            $data['recordsTotal'] = $datadb->get()->count();
            if (isset($_POST['search']['value'])) {
                $keyword = $_POST['search']['value'];
                $datadb->where(function ($query) use ($keyword) {
                    $query->where('u.nama_company', 'LIKE', '%' . $keyword . '%');
                    $query->orWhere('m.nama_lengkap', 'LIKE', '%' . $keyword . '%');
                    $query->orWhere('m.nik', 'LIKE', '%' . $keyword . '%');
                    $query->orWhere('m.jabatan', 'LIKE', '%' . $keyword . '%');
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
        // dd($data);
        return json_encode($data);
    }

    public function submit(Request $request)
    {
        $data = $request->all();
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            $decryptedUserId = Crypt::decrypt($data['user_id']);

            $action = $data['id'] == '' ? new Karyawan() : Karyawan::find($data['id']);
            $action->status = 'APPROVED';
            $action->approved_by = $decryptedUserId;
            $action->approved_date = now();
            $action->save();

            $actorAction = new Actor();
            $actorAction->users = $decryptedUserId;
            $actorAction->content = 'Akun Karyawan ' . $data['nama_lengkap'] . ' dengan username' . $data['nik'] . ' Telah di Verified oleh ' . $data['name'];
            $actorAction->action = 'Karyawan ' . $data['nama_lengkap'] . ' dengan username' . $data['nik'] . ' Telah di Verified';
            $actorAction->created_at = now();
            $actorAction->save();

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $result['message'] = $th->getMessage();
        }

        try {
            $phoneNumber = '+62' . ltrim($data['contact'], '0');
            $message = "Akun anda, {$data['nama_lengkap']} dengan username {$data['nik']} telah di Verified.";

            sendFonteNotification($phoneNumber, $message);
        } catch (\Throwable $notificationError) {
            \Log::error('Gagal mengirim notifikasi: ' . $notificationError->getMessage());
        }

        return response()->json($result);
    }

    public function submitUserExcel(Request $request)
    {
        $request->validate([
            'dataRows.*.username' => 'required|string|max:255',
            'dataRows.*.email' => 'required|email',
            'dataRows.*.password' => 'required|string|min:6',
            'dataRows.*.complete_name' => 'required|string|max:255',
            'dataRows.*.zone_code' => 'required|string|max:10',
            'dataRows.*.role_name' => 'required|string|max:50',
            'dataRows.*.gelar' => 'string|max:50|nullable',
            'dataRows.*.tempat_lahir' => 'required|string|max:100',
            'dataRows.*.tanggal_lahir' => 'required|date',
            'dataRows.*.provinsi' => 'required|string|max:100',
            'dataRows.*.kota' => 'required|string|max:100',
            'dataRows.*.kecamatan' => 'required|string|max:100',
            'dataRows.*.kelurahan' => 'required|string|max:100',
            'dataRows.*.no_handphone' => 'required|string|max:20',
            'dataRows.*.perusahaan' => 'required|string|max:255',
            'dataRows.*.perusahaan_cabang' => 'required|string|max:255',
            'dataRows.*.alamat' => 'required|string|max:255',
            'dataRows.*.jabatan' => 'required|string|max:50',
            'dataRows.*.domisili' => 'required|string|max:100',
        ]);

        $data = $request->all();
        DB::beginTransaction();

        $decryptedUserId = Crypt::decrypt($data['user_id']);
        try {
            foreach ($data['dataRows'] as $user) {
                $newUser = new Users();
                $newUser->username = $user['username'];
                $newUser->name = $user['complete_name'];
                $newUser->nik = $user['username'];
                $newUser->user_group = ($user['role_name'] === 'inputter') ? 4 : null;
                $newUser->password = Hash::make($user['password']);
                $newUser->save();

                $company = CompanyModel::whereRaw('LOWER(nama_company) = ?', [strtolower($user['perusahaan'])])->first();
                $companyId = $company ? $company->id : null;

                $branch = Branch::whereRaw('LOWER(nama_cabang) = ?', [strtolower($user['perusahaan_cabang'])])->first();
                $branchId = $branch ? $branch->id : null;

                $karyawan = new Karyawan();
                $karyawan->nama_lengkap = $user['complete_name'];
                $karyawan->tempat_lahir = $user['tempat_lahir'];
                $karyawan->tanggal_lahir = $user['tanggal_lahir'];
                $karyawan->nik = $newUser->nik;
                $karyawan->alamat = $user['alamat'];
                $karyawan->contact = $user['no_handphone'];
                $karyawan->jabatan = $user['jabatan'];
                $karyawan->email = $user['email'];
                $karyawan->provinsi = $user['provinsi'];
                $karyawan->kota = $user['kota'];
                $karyawan->kecamatan = $user['kecamatan'];
                $karyawan->kel_desa = $user['kelurahan'];
                $karyawan->domisili = $user['domisili'];
                $karyawan->gelar = $user['gelar'] === " - " ? null : $user['gelar'];
                $karyawan->zone_code = $user['zone_code'];
                $karyawan->warga_negara = 'Indonesia';
                $karyawan->status = 'DRAFT';

                if ($companyId) {
                    $karyawan->company = $companyId;
                }
                if ($branchId) {
                    $karyawan->branch = $branchId;
                }
                $karyawan->save();

                $actorAction = new Actor();
                $actorAction->users = $decryptedUserId;
                $actorAction->content =  'Akun Karyawan ' . $user['complete_name'] . ' telah diregistrasi oleh ' . $data['name'];
                $actorAction->action = 'Karyawan ' . $user['complete_name'] . ' telah melakukan registrasi';
                $actorAction->created_at = now();
                $actorAction->save();
            }

            DB::commit();
            return response()->json(['message' => 'Data berhasil disimpan'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Transaction failed: ' . $e->getMessage());
            return response()->json(['message' => 'Data gagal disimpan', 'error' => $e->getMessage()], 500);
        }
    }

    public function confirmReject(Request $request)
    {
        $data = $request->all();
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            $decryptedUserId = Crypt::decrypt($data['user_id']);

            $action = Karyawan::find($data['id']);
            $action->status = 'REJECTED';
            $action->approved_by = $decryptedUserId;
            $action->remarks = $data['remarks'];
            $action->save();

            $actorAction = new Actor();
            $actorAction->users = $decryptedUserId;
            $actorAction->content = 'Akun Karyawan ' . $data['nama_lengkap'] . ' Telah di Tolak oleh ' . $data['name'];
            $actorAction->action = 'Karyawan ' . $data['nama_lengkap'] . ' Telah di Tolak';
            $actorAction->created_at = now();
            $actorAction->save();

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            //throw $th;
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }

        try {
            $phoneNumber = '+62' . ltrim($data['contact'], '0');
            $message =  "Akun Anda, {$data['nama_lengkap']} dengan username {$data['nik']} telah di tolak, karena {$data['remarks']}. Jika ada pertanyaan silahkan kontak customer service kami, Terima Kasih.";

            sendFonteNotification($phoneNumber, $message);
        } catch (\Throwable $notificationError) {
            \Log::error('Gagal mengirim notifikasi: ' . $notificationError->getMessage());
        }
        return response()->json($result);
    }

    public function uploadExcel(Request $request)
    {

        $request->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls'
        ]);

        $data = Excel::toCollection(new KaryawanImport, $request->file('excelFile'));
        $sheetData = $data[0];
        return response()->json($sheetData->toArray());
    }

    public function delete(Request $request)
    {
        $data = $request->all();
        return view('web.approval_karyawan.modal.confirmdelete', $data);
    }
}
