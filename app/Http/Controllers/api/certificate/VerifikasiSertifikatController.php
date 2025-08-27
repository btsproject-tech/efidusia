<?php

namespace App\Http\Controllers\api\certificate;

use App\Http\Controllers\Controller;
use App\Models\Master\Actor;

use App\Models\Master\Karyawan;
use App\Models\Master\Saksi;
use Illuminate\Support\Facades\File;
use App\RequestCertificate;
use App\RequestCertificateContract;
use App\RequestCertificateContractStatus;
use App\RequestCertificateNotaris;
use App\SertificateMinuta;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\TemplateProcessor;

class VerifikasiSertifikatController extends Controller
{
    public function getTableName()
    {
        return "request_sertificate";
    }

    public function getData(Request $request)
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
        // dd(session('akses') == 'notaris');
        if (session('akses') == 'notaris') {
            $datadb->whereHas('RequestContract', function ($query) {
                $query->where('delegate_to', session('nik'));
                $query->where('delegate_to', '!=', null);
                $query->where('status', 'ON PROCESS');
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
                    $data_contract->no_sk = $data['data_sertificate']['no_sk'];
                    $data_contract->status = 'DONE';
                    $data_contract->waktu_sk = $data['data_sertificate']['waktu_sk'];
                    // $data_contract->tanggal_akta = $data['data_sertificate']['tanggal_akta'];
                    $data_contract->tanggal_sertifikat =  DateTime::createFromFormat('d-m-Y', $data['data_sertificate']['tanggal_sertifikat'])->format('Y-m-d');
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
                    $actor->content = session('user_name') . 'Telah menambahkan file SK pada no kontrak ' . $data_contract->contract_number;
                    $actor->action = "MENAMBHAKAN FILE SK PADA NO KONTRAK " . $data_contract->contract_number;
                    $actor->save();
                } else {
                    // Update Table Request
                    $data_request = RequestCertificate::find($id_req_ser);
                    $data_request->status =  'ON PROCESS';
                    $data_request->save();
                    // Update Table Contract
                    // list($nik, $name) = explode(' - ', $data['data_contract']['delegate_to']);

                    // dd($data_contract);
                    if ($data_contract) {
                        // Jika data ditemukan, lakukan update
                        // $data_contract->delegate_to = $nik;
                        // $data_contract->date_delegate = $data['data_contract']['date_delegate'];
                        // $data_contract->seq_number = $data['data_contract']['seq_numbers'];
                        $data_contract->status = 'APPROVE';
                        // $data_contract->remarks_verify = isset($data['data_contract']['remarks_verify']) ? $data['data_contract']['remarks_verify'] : '';
                        // $data_contract->billing_number_ahu = $data['data_contract']['billing_number_ahu'];
                        // $data_contract->date_input_ahu = $data['data_contract']['date_input_ahu'];
                        // $data_contract->name_pnbp = $data['data_contract']['name_pnbp'];
                        $data_contract->updater = session('user_id');
                        $data_contract->save();
                    }
                    // Update Table Contract Status
                    $data_contract_status = RequestCertificateContractStatus::where('request_sertificate_contract', $data['data_contract']['id'])->first();
                    // dd($data_contract_status);
                    if ($data_contract_status) {
                        $data_contract_status->creator = session('user_id');
                        $data_contract_status->status = 'VERIFIED';
                        if ($data_contract->status  == 'REJECT') {
                            $data_contract_status->remarks = 'Pengajuan ditolak oleh Vendor';
                        } else if ($data_contract->status  == 'APPROVE') {
                            $data_contract_status->remarks = 'Menunggu Proses Pengerjaan oleh Notaris';
                        }
                        $data_contract_status->save();
                    }


                    //Insert Table Actor
                    $actor = new Actor();
                    $actor->users = session('user_id');
                    $actor->content = $data_contract_status;
                    $actor->action = 'Permintaan SK Kontrak No. ' .  $data['data_request']['no_request'] . ' Telah di verifikasi';
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
        $datadb = RequestCertificate::with(['Creator', 'RequestContract', 'RequestContractStatus', 'RequestContract.RequestContractStatusDailyReport', 'RequestContract.UserDelegate.CompanyKaryawan', 'RequestContract.DataMinuta'])->where('id', $id);
        $data = $datadb->first();
        // dd($data->RequestContract);
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
        return view('web.verifikasi_certificate.modal.datausernotaris', $data);
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

    // public function submitMinuta(Request $request)
    // {
    //     $data = $request->all();
    //     // dd($data);
    //     $result['is_valid'] = false;
    //     DB::beginTransaction();
    //     try {

    //         if (isset($data['data_minuta'])) {

    //             // request_sertificate_contract
    //             $id_contract = $data['data_minuta'][0]['request_sertificate_contract'];
    //             $data_contract = RequestCertificateContract::find($id_contract);
    //             $data_contract->status = "FINISHED";
    //             $data_contract->updated_at = date('Y-m-d H:i:s');
    //             $data_contract->save();

    //             $data_contract_actor = RequestCertificateContract::with('RequestContract')->find($id_contract);
    //             $no_request = $data_contract_actor->RequestContract->no_request;
    //             $id_req = $data_contract_actor->RequestContract->id;


    //             // insert minuta
    //             foreach ($data['data_minuta'] as $key1 => $item) {
    //                 // sertificate_minuta
    //                 $data_minuta = new SertificateMinuta();
    //                 $data_minuta->request_sertificate_contract = $item['request_sertificate_contract'];
    //                 // $data_minuta->remarks = $item['remarks'];
    //                 // $data_minuta->contract_number = $item['contract_number'];
    //                 // $data_minuta->contract_job = $item['contract_job'];
    //                 // $data_minuta->debitur = $item['debitur'];
    //                 // $data_minuta->debitur_address = $item['debitur_address'];
    //                 // $data_minuta->debitur_price = $item['debitur_price'];
    //                 $file = $item['file'];
    //                 // Gunakan nama file yang diposting
    //                 $fileName = $item['file_name'];
    //                 // New file directory
    //                 $dir = 'berkas/document/minuta/';
    //                 $dir .= date('Y') . '/' . date('m');
    //                 $pathlamp = public_path() . '/' . $dir . '/';
    //                 // Create the directory if it doesn't exist
    //                 if (!File::isDirectory($pathlamp)) {
    //                     File::makeDirectory($pathlamp, 0777, true, true);
    //                 }
    //                 // Save the new file
    //                 if ($item['file'] != '') {
    //                     uploadFileFromBlobString($pathlamp, $fileName, $file);
    //                 } else {
    //                     File::put($pathlamp . $fileName, base64_decode($file));
    //                 }
    //                 // Update the database path
    //                 $dbpathlamp = '/' . $dir . '/';
    //                 $data_minuta->sertificate_file = isset($fileName) ? $fileName : $data_minuta->sertificate_file;
    //                 $data_minuta->sertificate_path = isset($dbpathlamp) ? $dbpathlamp : $data_minuta->sertificate_path;
    //                 $data_minuta->status = 'DRAFT';
    //                 $data_minuta->keterangan_sertificate = $item['keterangan'];
    //                 $data_minuta->creator  = session('user_id');
    //                 $data_minuta->save();


    //                 // insert request_sertificate_contract_status
    //                 $data_status = new RequestCertificateContractStatus();
    //                 $data_status->request_sertificate_contract = $item['request_sertificate_contract'];
    //                 $data_status->request_sertificate = $id_req;
    //                 $data_status->creator = session('user_id');
    //                 $data_status->status = "FINISHED";
    //                 $data_status->remarks = "Proses Pembuatan SK dan Minuta Selesai";
    //                 $data_status->save();

    //                 // insert Actor
    //                 $data_actor = new Actor();
    //                 $data_actor->users = session('user_id');
    //                 $data_actor->content = null;
    //                 $data_actor->action  = "Permintaan SK dengan no. permintaan " . $no_request . " Telah Selesai";
    //                 $data_actor->save();
    //             }

    //             $cek_status = cekStatusRequest($id_req);

    //             // update table request_sertificate
    //             $data_sertificate = RequestCertificate::find($id_req);
    //             $data_sertificate->status = $cek_status;
    //             $data_sertificate->updated_at = date('Y-m-d H:i:s');
    //             $data_sertificate->save();

    //             DB::commit();
    //             $result['is_valid'] = true;

    //             // $data_req = RequestCertificate::find($id_req);
    //             // $data_req->status = "FINISHED";
    //             // $data_req->updated_at = date('Y-m-d H:i:s');
    //             // $data_req->save();:
    //         }

    //         // dd($data['data_minuta']);
    //         // Send Notification
    //         $data_contract = RequestCertificateContract::find($data['data_minuta'][0]['request_sertificate_contract']);
    //         $data_company = Karyawan::with('CompanyKaryawan')->where('nik', $data_contract->creator)->first();
    //         // dd($data_company);
    //         if (env('FONTE_API_KEY') != null) {
    //             if (!empty($data_company)) {
    //                 $nama = $data_company->nama_lengkap;
    //                 $jumlah_item = count($data['data_minuta']);
    //                 $message = "Transaksi baru untuk " . $nama . " baru saja dibuat\nMinuta sejumlah : " . $jumlah_item . "\nPada No.Kontrak " . $data_contract->contract_number . "";
    //                 $no_hp = $data_company->CompanyKaryawan->no_hp;
    //                 sendFonteNotification($no_hp, $message);
    //             }
    //         }
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         $result['message'] = $th->getMessage();
    //         DB::rollBack();
    //     }
    //     return response()->json($result);
    // }


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
        $notaris = $data['notaris'];
        $result['is_valid'] = false;

        DB::beginTransaction();
        try {
            $datadb = RequestCertificate::with(['Creator', 'RequestContract', 'RequestContractStatus', 'Creator.Karyawan', 'RequestContract.UserDelegate.CompanyKaryawan', 'Creator.Karyawan.CompanyKaryawan'])
                ->where('id', $data['no_request'])
                ->whereHas('RequestContract', function ($query) use ($notaris) {
                    $query->where('delegate_to', $notaris);
                })
                ->whereNull('deleted')
                ->orderBy('id', 'desc')
                ->get();
            // $data = $datadb->toArray();
            // dd($data);

            $data_notif = [];
            $data_notif['total_pnpb'] = 0;
            foreach ($datadb as $key => $value) {
                foreach ($value->RequestContract as $key1 => $value1) {
                    if ($value1->status == "APPROVE" && $value1->flag_notif != 'TERKIRIM') {
                        $data_notif['nama_notaris'] = $value1->UserDelegate->nama_lengkap;

                        $data_notif['data_notif'][] = [
                            'id' => $value1->id,
                            'no_minuta' => $value1->seq_number,
                            'contract_number' => $value1->contract_number,
                            'pnbp' => cari_biaya_barang($value1->hutang_barang),
                        ];
                        $data_notif['total_pnpb'] = $data_notif['total_pnpb'] + cari_biaya_barang($value1->hutang_barang);
                        $data_notif['no_hp'] = isset($value1->UserDelegate->CompanyKaryawan->no_hp) ? $value1->UserDelegate->CompanyKaryawan->no_hp : "";
                    }
                }
            }
            $data_notif['foot_message'] = "Mohon untuk melakukan konfirmasi jika sudah melakukan pembayaran dengan me-reply pesan ini \n Terimakasih.";

            $data_notif['isi'] = [];
            $no = 1;
            $datetimenow = date('Y-m-d H:i:s');
            if (isset($data_notif['data_notif'])) {
                foreach ($data_notif['data_notif'] as $key => $value) {
                    $data_notif['isi'][] = $no++ . ". | " . $value['no_minuta'] . " | " . $value['contract_number'] . " | " . $value['pnbp'];
                }

                $message = "Halo, *"
                    . $data_notif['nama_notaris'] . "* "
                    . "Berikut Bill ID yang harus anda bayarkan :\n"
                    . "*Batch* :" . $datetimenow . "\n"
                    . "*Total* :" . $data_notif['total_pnpb'] . "\n"
                    . "*QTY* :" . count($data_notif['data_notif']) . "\n"
                    . "\n"
                    . "*No. | No.Minuta | Bill ID | PNBP*\n"
                    . implode("\n", $data_notif['isi']) . "\n\n"
                    . $data_notif['foot_message'] . "";


                // update status di req_contract
                $data_contract = RequestCertificateContract::where('request_sertificate', $data['no_request'])
                    ->where('delegate_to', $notaris)
                    ->update([
                        // 'status' => 'DONE',
                        'flag_notif' => 'TERKIRIM',
                    ]);

                // dd($data_contract);
                if ($data_contract > 0) {
                    DB::commit();
                }

                if (env('FONTE_API_KEY') != null) {
                    $no_hp = $data_notif['no_hp'];
                    sendFonteNotification($no_hp, $message);
                }

                $result['is_valid'] = true;
                $result['message'] = 'Success, Notfication sent';
            } else {
                $result['is_valid'] = false;
                $result['message'] = 'Maaf, tidak data yang ditemukan';

                return response()->json($result);
            }
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
        }
        return response()->json($result);
    }
    public function submitDelegate(Request $request)
    {
        $data = $request->all();
        // dd($data);
        list($nik, $name) = explode(' - ', $data['delegate_to']);
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {

            // insert to table request_sertificate_notaris
            $data_request_sertificate_notaris = new RequestCertificateNotaris();
            $data_request_sertificate_notaris->request_sertificate = $data['no_request'];
            $data_request_sertificate_notaris->users = $nik;
            $data_request_sertificate_notaris->status = 'ON PROCESS';
            $data_request_sertificate_notaris->save();

            // Assuming $ids adalah array yang berisi banyak ID
            $data_contract = RequestCertificateContract::whereIn('id', $data['id_kontrak'])->update([
                'delegate_to' => $nik,
                'date_delegate' => date('Y-m-d H:i:s'),
                'status' => 'ON PROCESS',
                'request_sertificate_notaris' => $data_request_sertificate_notaris->id,
            ]);


            if ($data_contract > 0) {
                // Insert Table Actor
                $actor = new Actor();
                $actor->users = session('user_id');
                $actor->content =  $data['delegate_to'] . "Jumlah Kontrak : " . $data_contract;
                $actor->action = "UPDATE DELEGATE TO -> " . $name;
                $actor->save();

                // Insert Table Actor
                $actor = new Actor();
                $actor->users = session('user_id');
                $actor->content =  json_encode($data_request_sertificate_notaris);
                $actor->action = "ADD DATA REQ_NOTARIS";
                $actor->save();

                // dd($actor->toArray());

                // get data notaris
                $data_notaris = Karyawan::with('CompanyKaryawan')->where('nik', $nik)->first();
                // get Notifikasi
                $message = "Transaksi baru untuk " . $name . " baru saja dibuat,\n Total : " . $data_contract . " tolong segera lakukan HIT!";
                $no_hp = $data_notaris->CompanyKaryawan->no_hp;
                // dd($no_hp);

                DB::commit();
                if (env('FONTE_API_KEY') != null) {
                    if (!empty($data_contract)) {
                        sendFonteNotification($no_hp, $message);
                    }
                }

                $result['message'] = 'Success, Delegate updated';
            }
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
        }
        return response()->json($result);
    }



    public function export(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $result = [
            'is_valid' => false,
            'message' => '',
            'order_item' => null,
            'order_detail' => null,
            'header' => null,
            'date_export' => date('Y-m-d H:i:s')
        ];

        try {
            $datadb = RequestCertificateContract::with(['RequestContract', 'RequestContractStatusDailyReport', 'RequestContract.Creator.Karyawan', 'DataMinuta'])
                ->whereNull('deleted')
                ->whereNotNull('seq_number')
                ->where('request_sertificate', $data['no_request'])
                ->where('status', 'APPROVE')
                ->where('delegate_to', $data['notaris'])
                ->orderBy('id', 'desc');

            $_data = $datadb->get()->toArray();
            // dd($_data);
            // VALIDASI DATA ALL DONE
            // foreach ($datadb->get() as $key => $value) {
            //     if ($value->status == "ON PROCESS") {
            //         $result['message'] = 'Maaf, Data status masih ada yang <br><b>"ON PROCESS"</b>';
            //         return response()->json($result);
            //     }
            // }
            // dd($_data);
            $result['header'] = [
                'IdTransaksi' => 'IdTransaksi',
                'NoKontrak' => 'NoKontrak',
                'NoMinuta' => 'NoMinuta',
                'BillId' => 'BillId',
                'Biaya PNBP' => 'Biaya PNBP',
            ];

            $detail_get = [];
            // dd($data);
            foreach ($_data as $key => $row) {
                $NoKontrak = empty($row['contract_number']) ? '-' : $row['contract_number'];
                $nilaiBarang = cari_biaya_barang($row['hutang_barang']);

                $detail_get[] = [
                    'IdTransaksi' => $row['id'],
                    'NoKontrak' => $NoKontrak,
                    'NoMinuta' => $row['seq_number'],
                    'Biaya PNBP' => $nilaiBarang,
                    'BillId' => '',
                ];
            }
            $result['order_detail'] = $detail_get;

            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
            $result['code'] = $th->getCode();
        }

        $result['date_export'] = date('Y-m-d H:i:s');
        $result['date_export_ymd'] = date('ymd');
        return response()->json($result, 200);
    }

    public function exportMinuta(Request $request)
    {
        $data = $request->all();
        $result['is_valid'] = false;
        // dd($data);
        DB::beginTransaction();
        try {

            $templatePath = public_path('assets/doc/template/template_minuta.docx');

            if (!file_exists($templatePath)) {
                throw new \Exception("Template file does not exist.");
            }

            $template_word = new TemplateProcessor($templatePath);
            $data_contract = RequestCertificateContract::with(['UserDelegate.CompanyKaryawan', 'UserCreator.Karyawan.CompanyKaryawan'])->find($data['id']);
            $id_notaris = $data_contract->UserDelegate->CompanyKaryawan->id;

            $data_saksi1 = Saksi::where('company', $id_notaris)
                ->where('status', 'SAKSI1')
                ->whereNull('deleted')
                ->orderBy('id', 'desc')
                ->first();
            $data_saksi2 = Saksi::where('company', $id_notaris)
                ->where('status', 'SAKSI2')
                ->whereNull('deleted')
                ->orderBy('id', 'desc')
                ->first();

            if ($data_saksi1 == null || $data_saksi2 == null) {
                throw new \Exception("Data Saksi belum ditemukan.");
            }
            // dd($data_saksi1);
            // Prepare data for template
            $jam_sekarang = date('H:i');
            $hari_sekarang = date('l');
            $date_now = date('Y-m-d');
            // dd($data_saksi);
            // mengisi data pada template

            $template_word->setValues([
                'no_minuta' => $data_contract->seq_number,
                'hari_ini' => terjemahkanHariKeIndonesia($hari_sekarang),
                'tgl_bln_thn_latin' => ubahFormatTglIdn($date_now) . '(' . terjemahkanTanggalKeKata($date_now) . ')',
                'waktu_wib_latin' => $jam_sekarang . ' WIB (' . terjemahkanJam($jam_sekarang) . ')',
                //
                'nama_orang_notaris' => $data_contract->UserDelegate->nama_lengkap,
                'gelar_orang_notaris' => $data_contract->UserDelegate->gelar,
                //
                'nama_finance' => $data_contract->UserCreator->Karyawan->nama_lengkap,
                'tempat_lahir_finance' => $data_contract->UserCreator->Karyawan->tempat_lahir,
                'tanggal_lahir_latin_finance' => ubahFormatTglIdn($data_contract->UserCreator->Karyawan->tanggal_lahir) . '(' . terjemahkanTanggalKeKata($data_contract->UserCreator->Karyawan->tanggal_lahir) . ')',
                'warga_negara_finance' => 'Indonesia',
                'alamat_kota_finance' => $data_contract->UserCreator->Karyawan->kota,
                'alamat_lengkap_finance' => $data_contract->UserCreator->Karyawan->alamat,
                'domisili_finance' => $data_contract->UserCreator->Karyawan->domisili,
                'provinsi_finance' => $data_contract->UserCreator->Karyawan->provinsi,
                'nik_ktp_finance' => $data_contract->UserCreator->Karyawan->nik,
                //
                'nama_debitur' => $data_contract->debitur,
                'tempat_lahir_debitur' => $data_contract->tempat_lahir,
                'tanggal_lahir_latin_debitur' => ubahFormatTglIdn($data_contract->tanggal_lahir) . '(' . terjemahkanTanggalKeKata($data_contract->tanggal_lahir) . ')',
                'warga_negara_debitur' => 'Indonesia',
                'pekerjaan_debitur' => $data_contract->pekerjaan,
                'alamat_lengkap_debitur' => $data_contract->alamat,
                'provinsi_debitur' => $data_contract->provinsi,
                'nik_debitur' => $data_contract->ktp,
                //
                'nama_pt_finance' => $data_contract->UserCreator->Karyawan->CompanyKaryawan->nama_company,
                'tgl_kontrak' => $data_contract->tanggal_kuasa,
                'no_kontrak' => $data_contract->contract_number,
                'hutang_barang' => ' Rp. ' . format_rp(intval($data_contract->hutang_barang)) . ' - ' . terbilang($data_contract->hutang_barang),
                'hutang_pokok' => ' Rp. ' . format_rp(intval($data_contract->hutang_pokok)) . ' - ' . terbilang($data_contract->hutang_pokok),
                'nilai_jaminan' => ' Rp. ' . format_rp(intval($data_contract->nilai_jaminan)) . ' - ' . terbilang($data_contract->nilai_jaminan),
                'merk_type' => $data_contract->merk . '/' . $data_contract->tipe,
                'tahun_kendaraan' => $data_contract->tahun,
                'no_rangka' => $data_contract->no_rangka,
                'no_mesin' => $data_contract->no_mesin,
                'nama_stnk' => $data_contract->pemilik_bpkb,
                //
                'nama_saksi1' => $data_saksi1->nama_lengkap,
                'tempat_lahir_saksi1' => $data_saksi1->tempat_lahir,
                'tanggal_lahir_latin_saksi1' => ubahFormatTglIdn($data_saksi1->tanggal_lahir) . '(' . terjemahkanTanggalKeKata($data_saksi1->tanggal_lahir) . ')',
                'warga_negara_saksi1' => 'Indonesia',
                'alamat_lengkap_saksi1' => $data_saksi1->alamat,
                'domisili_saksi1' => $data_saksi1->domisili,
                'provinsi_saksi1' => $data_saksi1->provinsi,
                'nik_saksi1' => $data_saksi1->nik,
                //
                'nama_saksi2' => $data_saksi2->nama_lengkap,
                'tempat_lahir_saksi2' => $data_saksi2->tempat_lahir,
                'tanggal_lahir_latin_saksi2' => ubahFormatTglIdn($data_saksi2->tanggal_lahir) . '(' . terjemahkanTanggalKeKata($data_saksi2->tanggal_lahir) . ')',
                'warga_negara_saksi2' => 'Indonesia',
                'alamat_lengkap_saksi2' => $data_saksi2->alamat,
                'domisili_saksi2' => $data_saksi2->domisili,
                'provinsi_saksi2' => $data_saksi2->provinsi,
                'nik_saksi2' => $data_saksi2->nik,
                //
                'nama_orang_finance' => $data_contract->UserCreator->nama_lengkap,
            ]);
            // end mengisi data pada template

            $dir = 'berkas/document/minuta/' . date('Y') . '/' . date('m');
            $pathlamp = public_path($dir);

            // Ensure the directory exists
            if (!is_dir($pathlamp)) {
                mkdir($pathlamp, 0777, true);
            }

            $template_word->saveAs($pathlamp . '/' . 'MINUTA_' . $data_contract->contract_number . '' . '.docx');


            // UPPDATE CONTRACT
            $data_contract->status = "FINISHED";
            $data_contract->updated_at = date('Y-m-d H:i:s');
            $data_contract->save();


            $data_contract_actor = RequestCertificateContract::with('RequestContract')->find($data_contract->id);
            $no_request = $data_contract_actor->RequestContract->no_request;
            $id_req = $data_contract_actor->RequestContract->id;

            $data_minuta = new SertificateMinuta();
            $data_minuta->sertificate_file = 'MINUTA_' . $data_contract->contract_number . '' . '.docx';
            $data_minuta->sertificate_path = $dir . '/';
            $data_minuta->request_sertificate_contract = $data_contract->id;
            $data_minuta->status = 'DRAFT';
            $data_minuta->keterangan_sertificate = null;
            $data_minuta->creator  = session('user_id');
            $data_minuta->save();


            // insert request_sertificate_contract_status
            $data_status = new RequestCertificateContractStatus();
            $data_status->request_sertificate_contract = $data_contract->id;
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

            $cek_status = cekStatusRequest($id_req);

            // update table request_sertificate
            $data_sertificate = RequestCertificate::find($id_req);
            $data_sertificate->status = $cek_status;
            $data_sertificate->updated_at = date('Y-m-d H:i:s');
            $data_sertificate->save();

            DB::commit();

            $result['is_valid'] = true;
            $result['file_path'] = $data_minuta->sertificate_path . '/' . $data_minuta->sertificate_file;


            // $data_company = Karyawan::with('CompanyKaryawan')->where('nik', $data_contract->creator)->first();
            // dd($data_company);
            // if (env('FONTE_API_KEY') != null) {
            //     if (!empty($data_company)) {
            //         $nama = $data_company->nama_lengkap;
            //         $jumlah_item = count($data['data_minuta']);
            //         $message = "Transaksi baru untuk " . $nama . " baru saja dibuat\nMinuta sejumlah : " . $jumlah_item . "\nPada No.Kontrak " . $data_contract->contract_number . "";
            //         $no_hp = $data_company->CompanyKaryawan->no_hp;
            //         sendFonteNotification($no_hp, $message);
            //     }
            // }


        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
        }

        // dd($result);
        return response()->json($result);
    }


    public function fungsiscanPDF(Request $request)
    {
        $data = $request->all();
        $data['file'];
        $result['is_valid'] = false;
        try {
            $data_pdf = ScanPDF($data['file']);

            $result['data_pdf'] = $data_pdf['data'];
            $result['is_valid'] = true;
            $result['message'] = 'Data berhasil terbaca..';
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
        }

        return response()->json($result);
    }

    public function import(Request $request)
    {
        $data = $request->all();
        $data_import = json_decode($data['data_contract']);
        $notaris = $data['notaris'];
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            $data_id = [];
            $cases_billId = [];
            $cases_biaya_pnbp = [];
            $contract_number = [];

            foreach ($data_import as $value) {
                $data_id[] = $value->id_kontrak;
                $cases_billId[] = "WHEN id = '{$value->id_kontrak}' THEN '{$value->billId}'";
                $cases_biaya_pnbp[] = "WHEN id = '{$value->id_kontrak}' THEN " . intval($value->biaya_pnbp);
            }

            $billId_case = implode(' ', $cases_billId);
            $biaya_pnbp_case = implode(' ', $cases_biaya_pnbp);

            DB::statement("
                UPDATE request_sertificate_contract
                SET
                    billId = CASE $billId_case END,
                    biaya_pnbp = CASE $biaya_pnbp_case END
                WHERE delegate_to = ? AND id IN (" . implode(',', $data_id) . ")
            ", [$notaris]);


            // insert Actor
            $user_notaris = Karyawan::with(['CompanyKaryawan'])->where('nik', $notaris)->first();
            $actor = new Actor();
            $actor->users = session('user_id');
            $actor->content = session('user_name') . ' Telah menambahkan BILL ID pada data kontrak ' . json_encode($contract_number);
            $actor->action = 'UPDATE BILL ID KONTRAK DENGAN NOTARIS ' . $user_notaris->CompanyKaryawan->nama_company;
            $actor->save();

            // COLLECT DATA FOR NOTIFICATION
            $data_contract = RequestCertificateContract::with(['UserDelegate.CompanyKaryawan'])
                ->where('delegate_to', $notaris)
                ->whereIn('id', $data_id)->get();

            $data_notif = [];
            $data_notif['total_pnpb'] = 0;
            $batch = "";
            foreach ($data_contract as $key => $value) {
                if ($value->status == "APPROVE" && $value->flag_notif != 'TERKIRIM') {
                    $data_notif['nama_notaris'] = $value->UserDelegate->nama_lengkap;
                    // Set batch hanya sekali
                    if (empty($batch)) {
                        $batch = $value->batch;
                    }
                    $data_notif['data_notif'][] = [
                        'id' => $value->id,
                        'no_minuta' => $value->seq_number,
                        'contract_number' => $value->contract_number,
                        'pnbp' => 'Rp. ' . format_rp($value->biaya_pnbp),
                        'billId' => $value->billId,
                    ];
                    $data_notif['total_pnpb'] = $data_notif['total_pnpb'] + $value->biaya_pnbp;
                    $data_notif['no_hp'] = isset($value->UserDelegate->CompanyKaryawan->no_hp) ? $value->UserDelegate->CompanyKaryawan->no_hp : "";
                }
            }

            $data_notif['foot_message'] = "Mohon untuk melakukan konfirmasi jika sudah melakukan pembayaran dengan me-reply pesan ini \nTerimakasih.";

            $data_notif['isi'] = [];
            $no = 1;
            $datetimenow = date('Y-m-d H:i:s');
            if (isset($data_notif['data_notif'])) {
                foreach ($data_notif['data_notif'] as $key => $value) {
                    $data_notif['isi'][] = $no++ . ". | " . $value['no_minuta'] . " | " . $value['contract_number'] . " | " . $value['billId'] . " | " . $value['pnbp'];
                }

                $message = "Halo, *"
                    . $data_notif['nama_notaris'] . "* "
                    . "Berikut Bill ID yang harus anda bayarkan :\n"
                    . "*Batch* :" . $batch . "\n"
                    . "*Total* : Rp. " . format_rp($data_notif['total_pnpb']) . "\n"
                    . "*QTY* :" . count($data_notif['data_notif']) . "\n"
                    . "\n"
                    . "*No. | No.Minuta | No.Kontrak | Bill ID | PNBP*\n"
                    . implode("\n", $data_notif['isi']) . "\n\n"
                    . $data_notif['foot_message'] . "";


                // update status di req_contract
                $data_contract_update_flag = RequestCertificateContract::whereIn('id', $data_id)
                    ->where('delegate_to', $notaris)
                    ->update([
                        // 'status' => 'DONE',
                        'flag_notif' => 'TERKIRIM',
                    ]);

                // dd($data_contract_update_flag);
                if ($data_contract_update_flag > 0) {
                    DB::commit();
                }

                if (env('FONTE_API_KEY') != null) {
                    $no_hp = $data_notif['no_hp'];
                    sendFonteNotification($no_hp, $message);
                }
            } else {
                $result['is_valid'] = false;
                $result['message'] = 'Maaf, tidak data yang ditemukan';

                return response()->json($result);
            }

            // DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
        }

        return response()->json($result);
    }

    public function showDataUserSaksi(Request $request)
    {

        $data = $request->all();
        return view('web.verifikasi_certificate.modal.datausersaksi', $data);
    }
    public function getDataUserSaksi(Request $request)
    {
        $data = $request->all();
        DB::enableQueryLog();
        $data['data'] = [];
        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $datadb = DB::table('saksi' . ' as m')
            ->select([
                'm.id',
                'c.nama_company',
                'm.nik',
                'm.nama_lengkap',
            ])
            ->join('company as c', 'c.id', 'm.company')
            ->whereNull('m.deleted')
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
                    $q->where('m.nik', 'like', '%' . $keyword . '%')
                        ->orWhere('m.nama_lengkap', 'like', '%' . $keyword . '%');
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
