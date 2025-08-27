<?php

namespace App\Http\Controllers\api\certificate;

use App\Http\Controllers\Controller;
use App\Models\Master\Actor;
use App\Models\Master\Document;
use App\Models\Master\DocumentTransaction;
use App\Models\Master\Karyawan;
use Illuminate\Support\Facades\File;
use App\RequestCertificate;
use App\RequestCertificateContract;
use App\RequestCertificateContractStatus;
use App\RequestCertificateNotaris;
use App\RequestCertificateNotarisAktaSela;
use App\SertificateMinuta;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestCertificateNotarisController extends Controller
{
    public function getTableName()
    {
        return "request_sertificate_notaris";
    }

    public function getData(Request $request)
    {
        // dd(session()->all());
        $data = $request->all();
        DB::enableQueryLog();
        $data['data'] = [];
        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $datadb = RequestCertificateNotaris::with(['UserNotaris', 'DataRequestCertificate', 'RequestContract'])
            ->whereNull('deleted')
            ->where('users', session('nik'))
            ->orderBy('id', 'desc');


        // if (isset($data['tgl_awal'])) {
        //     if ($data['tgl_awal'] != '') {
        //         $datadb->where(function ($q) use ($data) {
        //             return $q->where('date_request', '>=', $data['tgl_awal'])
        //                 ->where('date_request', '<=', $data['tgl_akhir']);
        //         });
        //     }
        // }
        // dd(session('akses') == 'notaris');
        // if (session('akses') == 'notaris') {
        //     $datadb->whereHas('RequestContract', function ($query) {
        //         $query->where('delegate_to', session('nik'));
        //         $query->where('delegate_to', '!=', null);
        //         $query->where('status', 'ON PROCESS');
        //     });
        // }


        // if (isset($_POST)) {
        //     $data['recordsTotal'] = $datadb->get()->count();
        //     if (isset($_POST['search']['value'])) {
        //         $keyword = $_POST['search']['value'];
        //         $datadb->where(function ($query) use ($keyword) {
        //             $query->where('no_request', 'LIKE', '%' . $keyword . '%')
        //                 ->orWhere('date_request', 'LIKE', '%' . $keyword . '%')
        //                 ->orWhere('status', 'LIKE', '%' . $keyword . '%')
        //                 ->orWhere('remarks', 'LIKE', '%' . $keyword . '%');
        //         });
        //     }

        //     if (isset($_POST['order'][0]['column'])) {
        //         $datadb->orderBy('id', $_POST['order'][0]['dir']);
        //     }

        //     $data['recordsFiltered'] = $datadb->get()->count();

        //     if (isset($_POST['length'])) {
        //         $datadb->limit($_POST['length']);
        //     }
        //     if (isset($_POST['start'])) {
        //         $datadb->offset($_POST['start']);
        //     }
        // }


        $data['data'] = $datadb->get()->toArray();
        $data['draw'] = $_POST['draw'];
        $query = DB::getQueryLog();
        // echo '<pre>';
        // dd($query);
        // print_r($query);die;
        return json_encode($data);
    }

    public function dashboard(Request $request)
    {
        // dd(session()->all());
        $data = $request->all();
        DB::enableQueryLog();
        $data['data'] = [];
        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $datadb = RequestCertificate::with(['Creator', 'RequestContract', 'RequestContractStatus', 'Creator.Karyawan', 'RequestContract.UserDelegate'])
            ->whereNull('deleted')
            ->orderBy('id', 'desc');

        if (isset($data['tgl_awal'])) {
            if ($data['tgl_awal'] != '') {
                $datadb->where(function ($q) use ($data) {
                    return $q->where('date_request', '>=', $data['tgl_awal'])
                        ->where('date_request', '<=', $data['tgl_akhir']);
                });
            }
        }

        if (session('akses') == 'notaris') {
            $datadb->whereHas('RequestContract', function ($query) {
                $query->where('delegate_to', session('nik'));
            });
        }

        if (isset($_POST)) {
            $data['recordsTotal'] = $datadb->get()->count();
            if (isset($_POST['search']['value'])) {
                $keyword = $_POST['search']['value'];
                $datadb->where(function ($query) use ($keyword) {
                    $query->where('no_request', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('date_request', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('status', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('remarks', 'LIKE', '%' . $keyword . '%');
                });

                // Cari Creator.name
                // $datadb->orWhereHas('Creator', function ($query) use ($keyword) {
                //     $query->orWhere('name', 'LIKE', '%' . $keyword . '%');
                // });
            }

            if (isset($_POST['order'][0]['column'])) {
                $datadb->orderBy('id', $_POST['order'][0]['dir']);
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
        // dd($query);
        // print_r($query);die;
        return json_encode($data);
    }

    public function submit(Request $request)
    {
        $data = $request->all();
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            if ($data['data_contract']['id'] == '') {
                // Insert Table Request
                $data_request = new RequestCertificate();
                $data_request->no_request = $data['data_request']['no_request'];
                $data_request->creator =  session('user_id');
                $data_request->date_request = $data['data_request']['date_request'];
                $data_request->remarks = $data['data_request']['remarks'];
                $data_request->status = "DRAFT";
                $data_request->save();
                $data_requestId = $data_request->id;
                // Insert Table Contract
                $data_contract = new RequestCertificateContract();
                $data_contract->request_sertificate = $data_requestId;
                $data_contract->delegate_to = $data['data_contract']['delegate_to'];
                $data_contract->date_delegate = $data['data_contract']['date_delegate'];
                $data_contract->remarks = $data['data_contract']['remarks_contract'];
                $data_contract->contract_number = $data['data_contract']['contract_number'];
                $data_contract->contract_job = $data['data_contract']['contract_job'];
                $data_contract->debitur = $data['data_contract']['debitur'];
                $data_contract->debitur_address = $data['data_contract']['debitur_address'];
                $data_contract->debitur_price = $data['data_contract']['debitur_price'];
                $data_contract->updater = session('user_id');
                $data_contract->status = 'DRAFT';
                $data_contract->save();
                $data_contractId = $data_contract->id;
                // Insert Table Contract Status
                $data_contract_status = new RequestCertificateContractStatus();
                $data_contract_status->request_sertificate = $data_requestId;
                $data_contract_status->request_sertificate_contract = $data_contractId;
                $data_contract_status->creator =  session('user_id');
                $data_contract_status->status = "DRAFT";
                $data_contract_status->remarks = "Menunggu Proses Verifikasi";
                $data_contract_status->save();
                //Insert Table Actor
                $actor = new Actor();
                $actor->users = session('user_id');
                $actor->content =  $data_request->no_request . ' - ' . 'request_sertificate';
                $actor->action = 'CREATED REQUEST CERTIFICATE ' . $data['data_request']['no_request'];
                $actor->save();
            } else {
                $data_contract = RequestCertificateContract::find($data['data_contract']['id']);
                $id_req_ser = $data_contract->request_sertificate;



                // UPDATE FILE SERTIFICATE
                if ($data_contract !== null && isset($data['data_sertificate']['file'])) {
                    $file = $data['data_sertificate']['file'];
                    // Gunakan nama file yang diposting
                    $fileName = $data['data_sertificate']['file_name'];
                    // New file directory
                    $dir = 'berkas/document/sertificate/';
                    $dir .= date('Y') . '/' . date('m');
                    $pathlamp = public_path() . '/' . $dir . '/';
                    // Create the directory if it doesn't exist
                    if (!File::isDirectory($pathlamp)) {
                        File::makeDirectory($pathlamp, 0777, true, true);
                    }
                    // Save the new file
                    if ($data['data_sertificate']['file'] != '') {
                        uploadFileFromBlobString($pathlamp, $fileName, $file);
                    } else {
                        File::put($pathlamp . $fileName, base64_decode($file));
                    }
                    // Update the database path
                    $dbpathlamp = '/' . $dir . '/';
                    $data_contract->sertificate_file = isset($fileName) ? $fileName : $data_contract->sertificate_file;
                    $data_contract->sertificate_path = isset($dbpathlamp) ? $dbpathlamp : $data_contract->sertificate_path;
                    $data_contract->status = 'DONE';

                    $data_contract->save();

                    // get data kontrak
                    $data_delegate = RequestCertificateContract::with(['UserDelegate', 'UserDelegate.CompanyKaryawan'])->find($data_contract->id);
                    $nama_company = $data_delegate->UserDelegate->CompanyKaryawan->nama_company;
                    // Insert Table Contract Status
                    $data_contract_status = new RequestCertificateContractStatus();
                    $data_contract_status->request_sertificate = $id_req_ser;
                    $data_contract_status->request_sertificate_contract = $data_contract->id;
                    $data_contract_status->creator =  session('user_id');
                    $data_contract_status->status = "DONE";
                    $data_contract_status->remarks = "Kontrak No. " . $data_contract->contract_number . " Telah diproses oleh notaris " .  $nama_company . "";
                    $data_contract_status->save();


                    //Insert Table Actor
                    $actor = new Actor();
                    $actor->users = session('user_id');
                    $actor->content = null;
                    $actor->action = "Kontrak No. " . $data_contract->contract_number . " Telah diproses oleh notaris " .  $nama_company . "";
                    $actor->save();
                } else {
                    // Update Table Request
                    $data_request = RequestCertificate::find($id_req_ser);
                    $data_request->status =  'ON PROCESS';
                    $data_request->save();
                    // Update Table Contract
                    list($nik, $name) = explode(' - ', $data['data_contract']['delegate_to']);

                    // dd($data_contract);
                    if ($data_contract) {
                        // Jika data ditemukan, lakukan update
                        $data_contract->delegate_to = $nik;
                        $data_contract->date_delegate = $data['data_contract']['date_delegate'];
                        $data_contract->seq_number = $data['data_contract']['seq_numbers'];
                        $data_contract->status = $data['data_contract']['status'];
                        $data_contract->remarks_verify = isset($data['data_contract']['remarks_verify']) ? $data['data_contract']['remarks_verify'] : '';
                        $data_contract->billing_number_ahu = $data['data_contract']['billing_number_ahu'];
                        $data_contract->date_input_ahu = $data['data_contract']['date_input_ahu'];
                        $data_contract->name_pnbp = $data['data_contract']['name_pnbp'];
                        $data_contract->updater = session('user_id');
                        $data_contract->save();
                    }
                    // Update Table Contract Status
                    $data_contract_status = RequestCertificateContractStatus::where('request_sertificate_contract', $data['data_contract']['id'])->first();
                    // dd($data_contract_status);
                    if ($data_contract_status) {
                        $data_contract_status->creator = session('user_id');
                        $data_contract_status->status = 'VERIFIED';
                        if ($data['data_contract']['status'] == 'REJECT') {
                            $data_contract_status->remarks = 'Pengajuan ditolak oleh Vendor';
                        } else if ($data['data_contract']['status'] == 'APPROVE') {
                            $data_contract_status->remarks = 'Menunggu Proses Pengerjaan oleh Notaris';
                        }
                        $data_contract_status->save();
                    }


                    //Insert Table Actor
                    $actor = new Actor();
                    $actor->users = session('user_id');
                    $actor->content = $data_contract_status;
                    $actor->action = 'Permintaan SK Telah Kontrak No. ' .  $data['data_request']['no_request'] . ' Telah di verifikasi';
                    $actor->save();
                }

                $cek_status = cekStatusRequest($id_req_ser);

                // update table request_sertificate
                $data_sertificate = RequestCertificate::find($id_req_ser);
                $data_sertificate->status = $cek_status;
                $data_sertificate->updated_at = date('Y-m-d H:i:s');
                $data_sertificate->save();

                // dd($data_status);
            }

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            //throw $th;
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }
        return response()->json($result);
    }

    public function confirmDelete(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {

            // Find the request certificate by id
            $req = RequestCertificate::find($data['id']);
            $no_request = $req->no_request;
            if ($req) {
                $req->deleted = date('Y-m-d H:i:s');
                $req->save();
            }

            // Find the related contract by request_sertificate id
            $contract = RequestCertificateContract::where('request_sertificate', $data['id'])->first();
            if ($contract) {
                $contract->deleted = date('Y-m-d H:i:s');
                $contract->save();
            }

            // Find the related contract status by request_sertificate id
            $contract_status = RequestCertificateContractStatus::where('request_sertificate', $data['id'])->first();
            if ($contract_status) {
                $contract_status->deleted = date('Y-m-d H:i:s');
                $contract_status->save();
            }

            DB::commit();
            $result['is_valid'] = true;
            //Insert Table Actor
            $actor = new Actor();
            $actor->users = session('user_id');
            $actor->content = $no_request;
            $actor->action = 'DELETED REQUEST CERTIFICATE ' . $no_request;
            $actor->save();
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }

        return response()->json($result);
    }





    public function getDetailData($id)
    {
        DB::enableQueryLog();
        // $datadb = RequestCertificateContract::with([
        //     'RequestCertificate',
        //     'RequestCertificateNotaris',
        //     'RequestCertificate.Creator',
        //     'RequestContractStatusDailyReport'
        // ])
        //     ->where('request_sertificate', $id)
        //     ->where('delegate_to', session('nik'))
        //     ->whereNull('deleted')
        //     ->orderBy('id', 'desc');
        $datadb = RequestCertificateNotaris::with(['UserNotaris', 'DataRequestCertificate', 'RequestContract'])
            ->whereNull('deleted')
            ->find($id);
        $data = $datadb;
        // dd($datadb);
        // dd($data->RequestContract[0]->RequestContractStatusDailyReport);
        // $data['biaya'] = cari_biaya_barang(82);
        // dd($data['biaya']);
        $query = DB::getQueryLog();
        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $data = $request->all();
        return view('web.verifikasi_certificate.modal.confirmdelete', $data);
    }

    public function showDataUserNotaris(Request $request)
    {
        $data = $request->all();
        return view('web.verifikasi_certificate_notaris.modal.datausernotaris', $data);
    }



    public function getDataUserNotaris(Request $request)
    {
        $data = $request->all();
        DB::enableQueryLog();
        $data['data'] = [];
        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $datadb = DB::table('users' . ' as m')
            ->select([
                'm.id',
                'ug.group',
                'c.nama_company',
                'k.nik',
                'k.nama_lengkap',
            ])
            ->join('users_group as ug', 'ug.id', 'm.user_group')
            ->join('karyawan as k', 'k.nik', 'm.nik')
            ->join('company as c', 'c.id', 'k.company')
            ->whereNull('m.deleted')
            ->where('m.user_group', 9)
            ->orderBy('m.id', 'desc');
        // $datadb = User::with(['Karyawan', 'Karyawan.CompanyKaryawan'])->whereHas('Karyawan', function ($query) {
        //     $query->where('company', 3);
        // })->whereNull('deleted');
        // dd($data_db->get()->toArray());
        // dd($datadb->get());


        if (isset($_POST)) {
            $data['recordsTotal'] = $datadb->get()->count();
            if (isset($_POST['search']['value'])) {
                $keyword = $_POST['search']['value'];
                $datadb->where(function ($q) use ($keyword) {
                    $q->where('k.nik', 'like', '%' . $keyword . '%')
                        ->orWhere('k.nama_lengkap', 'like', '%' . $keyword . '%');
                });
            }
            if (isset($_POST['order'][0]['column'])) {
                $datadb->orderBy('id', $_POST['order'][0]['dir']);
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
        // dd($query);
        // print_r($query);die;
        return json_encode($data);
    }

    public function searchDataItem(Request $request)
    {
        $data = $request->all();
        $result['data'] = [];
        $result['is_valid'] = false;
        try {
            $datadb = RequestCertificateContract::with(['UserDelegate', 'RequestContract'])
                ->where('request_sertificate', $data['id_request'])
                ->where('contract_number', 'LIKE', '%' . $data['no_kontrak'] . '%')
                ->where('status', 'LIKE', '%' . $data['status'] . '%')
                ->get();
            $result['data'] = $datadb->toArray();
            // dd($result['data']);
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
        }
        return response()->json($result);
    }

    public function submitMinuta(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {

            if (isset($data['data_minuta'])) {

                // request_sertificate_contract
                $id_contract = $data['data_minuta'][0]['request_sertificate_contract'];
                $data_contract = RequestCertificateContract::find($id_contract);
                $data_contract->status = "FINISHED";
                $data_contract->updated_at = date('Y-m-d H:i:s');
                $data_contract->save();

                $data_contract_actor = RequestCertificateContract::with('RequestContract')->find($id_contract);
                $no_request = $data_contract_actor->RequestContract->no_request;
                $id_req = $data_contract_actor->RequestContract->id;


                // insert minuta
                foreach ($data['data_minuta'] as $key1 => $item) {
                    // sertificate_minuta
                    $data_minuta = new SertificateMinuta();
                    $data_minuta->request_sertificate_contract = $item['request_sertificate_contract'];
                    // $data_minuta->remarks = $item['remarks'];
                    // $data_minuta->contract_number = $item['contract_number'];
                    // $data_minuta->contract_job = $item['contract_job'];
                    // $data_minuta->debitur = $item['debitur'];
                    // $data_minuta->debitur_address = $item['debitur_address'];
                    // $data_minuta->debitur_price = $item['debitur_price'];
                    $file = $item['file'];
                    // Gunakan nama file yang diposting
                    $fileName = $item['file_name'];
                    // New file directory
                    $dir = 'berkas/document/minuta/';
                    $dir .= date('Y') . '/' . date('m');
                    $pathlamp = public_path() . '/' . $dir . '/';
                    // Create the directory if it doesn't exist
                    if (!File::isDirectory($pathlamp)) {
                        File::makeDirectory($pathlamp, 0777, true, true);
                    }
                    // Save the new file
                    if ($item['file'] != '') {
                        uploadFileFromBlobString($pathlamp, $fileName, $file);
                    } else {
                        File::put($pathlamp . $fileName, base64_decode($file));
                    }
                    // Update the database path
                    $dbpathlamp = '/' . $dir . '/';
                    $data_minuta->sertificate_file = isset($fileName) ? $fileName : $data_minuta->sertificate_file;
                    $data_minuta->sertificate_path = isset($dbpathlamp) ? $dbpathlamp : $data_minuta->sertificate_path;
                    $data_minuta->status = 'DRAFT';
                    $data_minuta->keterangan_sertificate = $item['keterangan'];
                    $data_minuta->creator  = session('user_id');
                    $data_minuta->save();


                    // insert request_sertificate_contract_status
                    $data_status = new RequestCertificateContractStatus();
                    $data_status->request_sertificate_contract = $item['request_sertificate_contract'];
                    $data_status->request_sertificate = $id_req;
                    $data_status->creator = session('user_id');
                    $data_status->status = "FINISHED";
                    $data_status->remarks = "Proses Pembuatan SK dan Minuta Selesai";
                    $data_status->save();

                    // insert Actor
                    $data_actor = new Actor();
                    $data_actor->users = session('user_id');
                    $data_actor->content = null;
                    $data_actor->action  = "Permintaan SK dengan no. permintaan " . $no_request . " Telah Selesai";
                    $data_actor->save();
                }

                $cek_status = cekStatusRequest($id_req);

                // update table request_sertificate
                $data_sertificate = RequestCertificate::find($id_req);
                $data_sertificate->status = $cek_status;
                $data_sertificate->updated_at = date('Y-m-d H:i:s');
                $data_sertificate->save();

                DB::commit();
                $result['is_valid'] = true;

                // $data_req = RequestCertificate::find($id_req);
                // $data_req->status = "FINISHED";
                // $data_req->updated_at = date('Y-m-d H:i:s');
                // $data_req->save();:
            }

            // dd($data['data_minuta']);
            // Send Notification
            $data_contract = RequestCertificateContract::find($data['data_minuta'][0]['request_sertificate_contract']);
            $data_company = Karyawan::with('CompanyKaryawan')->where('nik', $data_contract->creator)->first();
            // dd($data_company);
            if (env('FONTE_API_KEY') != null) {
                if (!empty($data_company)) {
                    $nama = $data_company->nama_lengkap;
                    $jumlah_item = count($data['data_minuta']);
                    $message = "Transaksi baru untuk " . $nama . " baru saja dibuat\nMinuta sejumlah : " . $jumlah_item . "\nPada No.Kontrak " . $data_contract->contract_number . "";
                    $no_hp = $data_company->CompanyKaryawan->no_hp;
                    sendFonteNotification($no_hp, $message);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }
        return response()->json($result);
    }


    public function cariBiaya(Request $request)
    {
        $data = $request->all();
        $result = cari_biaya_barang($data['nilai_barang']);
        return response()->json($result);
    }
    public function sendNotifikasi(Request $request)
    {
        $data = $request->all();
        // dd($data);
        // dd(session()->all());
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            $data_request_notaris = RequestCertificateNotaris::find($data['idr_notaris']);
            $data_request_notaris->status = 'DONE';
            $data_request_notaris->save();

            $data_contract = RequestCertificateContract::where('request_sertificate_notaris', $data['idr_notaris'])
                ->update([
                    'status' => 'APPROVE',
                    'batch' => date('Y-m-d H:i:s') . "|" . $data_request_notaris->id,
                ]);

            $data_contract_ = RequestCertificateContract::where('request_sertificate_notaris', $data['idr_notaris'])->get();
            $batch = $data_contract_[0]->batch;
            $data_insert_contract = [];
            foreach ($data_contract_ as $item) {
                $data_insert_contract[] = [
                    'request_sertificate' => $data_request_notaris->request_sertificate,
                    'request_sertificate_contract' => $item->id,
                    'creator' => session('user_id'),
                    'status' => "VERIFIED",
                    'created_at' => date('Y-m-d H:i:s'),
                    'remarks' => "Kontrak No. " . $item->contract_number . " Telah diproses oleh notaris " .  session('area_kerja') . "",
                ];
            }

            // Insert Table Contract Status
            RequestCertificateContractStatus::insert($data_insert_contract);

            $cek_status = cekStatusRequest($data_request_notaris->request_sertificate);

            $actor = new Actor();
            $actor->users = session('user_id');
            $actor->content = session('user_name') . ' Telah menyelesaikan pekerjaan  pada batch id : ' . $data['idr_notaris'];
            $actor->action = 'MENYELESAIKAN PERKEJAAN BATCH ID ' . $data['idr_notaris'];
            $actor->save();

            if (env('FONTE_API_KEY') != null) {
                // get data vendor
                $data_req = RequestCertificate::with(['Creator.Karyawan.CompanyKaryawan'])->find($data_request_notaris->request_sertificate);
                $data_vendor =  Karyawan::with('CompanyKaryawan')->where('company', 3)->first();
                $data_notaris = Karyawan::with('CompanyKaryawan')->where('nik', $data_request_notaris->users)->first();
                $notaris = $data_notaris->CompanyKaryawan->nama_company;
                $customer = $data_req->Creator->Karyawan->CompanyKaryawan->nama_company;
                $no = 1;
                $message = "";
                $message .= "*Notaris* : " . $notaris . "\n";
                $message .= "*Customer* : " . $customer . "\n";
                $message .= "*Batch* : " . $batch . "\n";
                $message .= "*No Request* : " . $data_req->no_request . "\n";
                $message .= "*QTY* : " . count($data_contract_) . "\n";
                $message .= "*status* : Done\n";
                $message .= "\n";
                $message .= "No. | No.Minuta |\n";
                foreach ($data_contract_ as $key => $value) {
                    $message .= $no . ". | " . $value->seq_number . "\n";
                    $no++;
                }
                // dd($message);
                // get Notifikasi
                $no_hp = $data_vendor->CompanyKaryawan->no_hp;
                sendFonteNotification($no_hp, $message);
            }

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }
        return response()->json($result);
    }
    public function konfirmasiPembayaran(Request $request)
    {
        $data = $request->all();
        // dd($data);
        // dd(session()->all());
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            $data_request_notaris = RequestCertificateNotaris::find($data['idr_notaris']);
            // $data_request_notaris->status = 'DONE';
            // $data_request_notaris->save();

            $data_contract = RequestCertificateContract::where('request_sertificate_notaris', $data['idr_notaris'])
                ->update([
                    'status_pembayaran' => 'DONE',
                ]);

            $data_contract_ = RequestCertificateContract::where('request_sertificate_notaris', $data['idr_notaris'])->get();
            $batch = $data_contract_[0]->batch;
            $cek_status = cekStatusRequest($data_request_notaris->request_sertificate);

            if (env('FONTE_API_KEY') != null) {
                // get data vendor
                $data_req = RequestCertificate::with(['Creator.Karyawan.CompanyKaryawan'])->find($data_request_notaris->request_sertificate);
                $data_vendor =  Karyawan::with('CompanyKaryawan')->where('company', 3)->first();
                $total_biaya_pnbp = 0;
                foreach ($data_contract_ as $key => $value) {
                    $total_biaya_pnbp += $value->biaya_pnbp;
                }
                // $data_notaris = Karyawan::with('CompanyKaryawan')->where('nik', $data_request_notaris->users)->first();
                // $notaris = $data_notaris->CompanyKaryawan->nama_company;
                // $customer = $data_req->Creator->Karyawan->CompanyKaryawan->nama_company;

                // insert Actor
                $actor = new Actor();
                $actor->users = session('user_id');
                $actor->content = session('user_name') . ' Telah melakukan konfirmasi pembayaran pada batch ' . $batch;
                $actor->action = 'KONFIRMASI PEMBAYARAN PADA BATCH  ' . $batch;
                $actor->save();

                $no = 1;
                $message = "";
                $message = "Halo, *"
                    . $data_vendor->CompanyKaryawan->nama_company . "* "
                    . "Berikut Bill ID dari anda yang sudah kami bayarkan :\n";
                $message .= "*Batch* : " . $batch . "\n";
                $message .= "*No Request* : " . $data_req->no_request . "\n";
                $message .= "*Total* : Rp." . format_rp($total_biaya_pnbp) . "\n";
                $message .= "*QTY* : " . count($data_contract_) . "\n";
                $message .= "*Status Pembayaran* : Done\n";
                $message .= "\n";
                $message .= "*No. | No.Minuta | No.Kontrak | Bill ID | PNBP*\n";
                foreach ($data_contract_ as $key => $value) {
                    $message .= $no . ". | " . $value->seq_number . " | " . $value->contract_number . " | " . $value->billId . " | Rp." . format_rp($value->biaya_pnbp) . "\n";
                    $no++;
                }
                $message .= "\nTerimakasih";
                // dd($message);
                // get Notifikasi
                $no_hp = $data_vendor->CompanyKaryawan->no_hp;
                sendFonteNotification($no_hp, $message);
            }

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }
        return response()->json($result);
    }
    public function generateNumber(Request $request)
    {
        $data = $request->all();
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            // UPDATE NOTARIS
            $data_request_notaris = RequestCertificateNotaris::find($data['idr_notaris']);
            $data_request_notaris->no_minuta = $data['no_notaris'];
            // $data_request_notaris->status = 'DONE';
            $data_request_notaris->save();

            // UPDATE CONTRACT
            $no_minuta = intval($data['no_notaris']) + 1;
            $data_contract = RequestCertificateContract::where('request_sertificate_notaris', $data['idr_notaris'])->get();
            $delete_data_akta_sela = RequestCertificateNotarisAktaSela::where('seritifcate_notaris', $data_request_notaris->id)->delete();

            foreach ($data_contract as $key => $value) {
                $waktu_jeda = $data['waktu_jeda'] + $data['waktu_jeda'] * $key;
                $value->seq_number = $no_minuta++;
                $value->waktu_tgl_notaris = tambahWaktuJeda($data['waktu'], $waktu_jeda);
                $value->status_dari_notaris = 1;
                $value->save();

                $data_akta_sela = new RequestCertificateNotarisAktaSela();
                $data_akta_sela->request_sertificate_contract = $value->id;
                $data_akta_sela->seritifcate_notaris = $data_request_notaris->id;
                $data_akta_sela->time_input = $data['waktu'];
                $data_akta_sela->jeda_input = $data['waktu_jeda'];
                $data_akta_sela->no_minuta = $value->seq_number;
                $data_akta_sela->save();
            }


            // dd($data_contract->toArray());
            // Insert Table Actor
            $actor = new Actor();
            $actor->users = session('user_id');
            $actor->content = session('user_name') . ' Telah melakukan Generate No. Minuta : ' . $data['no_notaris'];
            $actor->action = 'GENERATE NO . MINUTA BATCH ID ' . $data['idr_notaris'] . ' : ' . $data['no_notaris'];
            $actor->save();
            // dd($actor->toArray());
            DB::commit();
            $result['is_valid'] = true;
            $result['message'] = 'Generate No, Minuta Successfully';
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
        }
        return response()->json($result);
    }
    public function inputAktaSela(Request $request)
    {
        $data = $request->all();
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            // dd($data);
            $parts = explode('/', $data['no_minuta']);
            $angka_depan = $parts[0];
            // dd($angka_depan);
            // UPDATE CONTRACT
            // dd(intval($data['no_notaris']));
            $no_minuta = intval($data['new_number_generate']) + 1;
            $data_contract = RequestCertificateNotarisAktaSela::with(['RequestContract'])
                ->where('seritifcate_notaris', $data['no_notaris'])
                ->where('no_minuta', '>=', $angka_depan) // Kondisi untuk mengambil no_minuta yang lebih besar atau sama dengan 3
                ->orderBy('no_minuta', 'asc')
                ->get();
            // dd($data_contract->toArray());
            foreach ($data_contract as $key => $value) {
                foreach ($value->RequestContract as $key1 => $value1) {
                    $waktu_jeda = $data['waktu_jeda'] + $data['waktu_jeda'] * $key;
                    $value1->seq_number = $no_minuta++;
                    $value1->waktu_tgl_notaris = tambahWaktuJeda($data['waktu'], $waktu_jeda);
                    $value1->status_dari_notaris = 1;
                    $value1->save();
                }

                // update akata sela
                $value->no_minuta = $value1->seq_number;
                $value->jeda_input = $data['waktu_jeda'];
                $value->time_input = $data['waktu'];
                $value->save();
            }
            // dd($data_contract->toArray());
            // Insert Table Actor
            $actor = new Actor();
            $actor->users = session('user_id');
            $actor->content = session('user_name') . ' Telah melakukan Generate No. Minuta : ' . $data['no_notaris'];
            $actor->action = 'GENERATE SELA NO. MINUTA, BATCH ID ' . $data['no_notaris'] . ' (' . $angka_depan . ' >++) : ' . $data['new_number_generate'];
            $actor->save();

            DB::commit();
            $result['is_valid'] = true;
            $result['message'] = 'Generate No, Minuta Successfully';
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
        }
        return response()->json($result);
    }

    public function downloadAll(Request $request)
    {
        $data = $request->all();
        $result['is_valid'] = false;

        try {
            $zip_warkah = downloadZipWarkah($data['id_kontrak']);
            if ($zip_warkah['is_valid'] == true) {
                $result['is_valid'] = true;
                $result['file_url'] = $zip_warkah['file_url'];
            }
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
        }

        return response()->json($result);
    }
}
