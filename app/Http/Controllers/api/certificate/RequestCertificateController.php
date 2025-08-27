<?php

namespace App\Http\Controllers\api\certificate;

use App\Http\Controllers\Controller;
use App\Models\Master\Actor;
use App\Models\Master\CompanyModel;
use App\Models\Master\DocumentTransaction;
use App\Models\Master\Karyawan;
use App\Models\Transaksi\Quotation;


use App\RequestCertificate;
use App\RequestCertificateContract;
use App\RequestCertificateContractStatus;
use App\SertificateWarkah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class RequestCertificateController extends Controller
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
        $datadb = RequestCertificate::with(['Creator', 'RequestContract', 'RequestContractStatus', 'Creator.Karyawan'])
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
        if (session('akses') != 'superadmin') {

            // Filter berdasarkan perusahaan (id_company) dari Creator -> Karyawan
            $datadb->whereHas('Creator.Karyawan', function ($query) {
                $query->where('company', session('id_company'));
            });
        }
        // dd($datadb->get());
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
        return json_encode($data);
    }



    public function submit(Request $request)
    {
        $data = $request->all();
        $result['is_valid'] = false;
        $no_req = generateNoSertifikat();
        $data_no_kontrak = [];
        DB::beginTransaction();
        try {
            if ($data['data_request']['id'] == '') {
                // Insert Table Request
                $data_request = new RequestCertificate();
                $data_request->no_request = $no_req;
                $data_request->creator =  session('user_id');
                $data_request->date_request = $data['data_request']['date_request'];
                $data_request->remarks = $data['data_request']['remarks'];
                $data_request->status = "DRAFT";
                $data_request->save();
                $data_requestId = $data_request->id;
                // Insert Table Contract
                if (!empty($data['data_contract'])) {
                    $contracts = [];
                    $contractStatuses = [];

                    foreach (json_decode($data['data_contract']) as $key => $value) {
                        // Persiapkan data contract

                        $contracts[] = [
                            'request_sertificate' => $data_requestId,
                            'remarks' => $value->remarks_contract ?? null,
                            'contract_number' => $value->contract_number ?? null,
                            'no_perjanjian_kontrak' => $value->no_perjanjian_kontrak ?? null,
                            'contract_job' => $value->contract_job ?? null,
                            'debitur' => $value->debitur ?? null,
                            'alamat' => $value->alamat ?? null,
                            'debitur_price' => $value->debitur_price ?? null,
                            'status' => 'DRAFT',
                            'pemberi_fidusia' => $value->pemberi_fidusia ?? null,
                            'jenis_kelamin' => $value->jenis_kelamin ?? null,
                            'tempat_lahir' => $value->tempat_lahir ?? null,
                            'tanggal_lahir' => $value->tanggal_lahir ?? null,
                            'pekerjaan' => $value->pekerjaan ?? null,
                            'creator' => session('user_id'),
                            'rt' => $value->rt ?? null,
                            'rw' => $value->rw ?? null,
                            'kelurahan' => $value->kelurahan ?? null,
                            'kecamatan' => $value->kecamatan ?? null,
                            'kabupaten' => $value->kabupaten ?? null,
                            'provinsi' => $value->provinsi ?? null,
                            'kode_pos' => $value->kode_pos ?? null,
                            'ktp' => $value->ktp ?? null,
                            'npwp' => $value->npwp ?? null,
                            'no_telp' => $value->no_telp ?? null,
                            'status_perkawinan' => $value->status_perkawinan ?? null,
                            'nama_pasangan' => $value->nama_pasangan ?? null,
                            'tanggal_kuasa' => $value->tanggal_kuasa ?? null,
                            'hutang_pokok' => $value->hutang_pokok ?? null,
                            'nilai_jaminan' => $value->nilai_jaminan ?? null,
                            'hutang_barang' => $value->hutang_barang ?? null,
                            'merk' => $value->merk ?? null,
                            'tipe' => $value->tipe ?? null,
                            'tahun' => $value->tahun ?? null,
                            'warna' => $value->warna ?? null,
                            'no_rangka' => $value->no_rangka ?? null,
                            'no_mesin' => $value->no_mesin ?? null,
                            'nopol' => $value->nopol ?? null,
                            'pemilik_bpkb' => $value->pemilik_bpkb ?? null,
                            'nomor_bpkb' => $value->nomor_bpkb ?? null,
                            'customer_tipe' => $value->customer_tipe ?? null,
                            'tgl_awal_tenor' => $value->tgl_awal_tenor ?? null,
                            'tgl_akhir_tenor' => $value->tgl_akhir_tenor ?? null,
                            'type_produk' => $value->type_produk ?? null,
                            'cab' => $value->cab ?? null,
                            'rep' => $value->rep ?? null,
                            'kondisi' => $value->kondisi ?? null,
                            'created_at' => date('Y-m-d H:i:s')
                        ];

                        // Persiapkan data contract status
                        $contractStatuses[] = [
                            'request_sertificate' => $data_requestId,
                            'status' => 'DRAFT',
                            'remarks' => 'Menunggu Proses Verifikasi',
                            'creator' => session('user_id'),
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    }


                    // Insert massal data contracts
                    RequestCertificateContract::insert($contracts);

                    // Insert massal data contract statuses
                    RequestCertificateContractStatus::insert($contractStatuses);
                }

                if (cek_duplikasi_no_kontrak($data_no_kontrak)) {
                    $result['is_valid'] = true;
                } else {
                    $result['is_valid'] = false;
                    $result['message'] = "No. Kontrak tidak boleh sama";
                    DB::rollBack();
                    return $result;
                }

                //Insert Table Actor
                $actor = new Actor();
                $actor->users = session('user_id');
                $actor->content =  $data_request->no_request . ' - ' . 'request_sertificate';
                $actor->action = 'CREATED REQUEST CERTIFICATE ' . $no_req;
                $actor->save();

                DB::commit();
                // Send Notification
                $data_company = Karyawan::with('CompanyKaryawan')->where('company', 3)->first();
                // dd($data_company);
                if (env('FONTE_API_KEY') != null) {
                    if (!empty($data_company)) {
                        // $nama = $data_company->nama_lengkap;
                        $customer = session('area_kerja');
                        // $nama_company = $data_company->CompanyKaryawan->nama_company;
                        $jumlah_item = count($contracts);
                        $message = "*Customer* : " . $customer  . "\n" .
                            " *Uploader* : " . session('nama_lengkap') . "\n" .
                            " *Total New* : " . $jumlah_item . "\n" .
                            " *Total Failed* : " . 0 . "\n" .
                            " Mode : LIVE";
                        $no_hp = $data_company->CompanyKaryawan->no_hp;
                        // dd($data_request);
                        sendFonteNotification($no_hp, $message);
                    }
                }
            } else {
                // Update Table Request
                $data_request = RequestCertificate::find($data['data_request']['id']);
                $data_request->creator =  session('user_id');
                $data_request->date_request = $data['data_request']['date_request'];
                $data_request->remarks = $data['data_request']['remarks'];
                $data_request->status = "DRAFT";
                $data_request->save();

                // Delete Table Contract
                RequestCertificateContract::where('request_sertificate', $data['data_request']['id'])->delete();
                RequestCertificateContractStatus::where('request_sertificate', $data['data_request']['id'])->delete();
                if (!empty($data['data_contract'])) {
                    $contracts = [];
                    $contractStatuses = [];

                    foreach (json_decode($data['data_contract']) as $key => $value) {
                        // Persiapkan data contract

                        $contracts[] = [
                            'request_sertificate' => $data['data_request']['id'],
                            'remarks' => $value->remarks_contract ?? null,
                            'contract_number' => $value->contract_number ?? null,
                            'no_perjanjian_kontrak' => $value->no_perjanjian_kontrak ?? null,
                            'contract_job' => $value->contract_job ?? null,
                            'debitur' => $value->debitur ?? null,
                            'alamat' => $value->alamat ?? null,
                            'debitur_price' => $value->debitur_price ?? null,
                            'status' => 'DRAFT',
                            'pemberi_fidusia' => $value->pemberi_fidusia ?? null,
                            'jenis_kelamin' => $value->jenis_kelamin ?? null,
                            'tempat_lahir' => $value->tempat_lahir ?? null,
                            'tanggal_lahir' => $value->tanggal_lahir ?? null,
                            'pekerjaan' => $value->pekerjaan ?? null,
                            'creator' => session('user_id'),
                            'rt' => $value->rt ?? null,
                            'rw' => $value->rw ?? null,
                            'kelurahan' => $value->kelurahan ?? null,
                            'kecamatan' => $value->kecamatan ?? null,
                            'kabupaten' => $value->kabupaten ?? null,
                            'provinsi' => $value->provinsi ?? null,
                            'kode_pos' => $value->kode_pos ?? null,
                            'ktp' => $value->ktp ?? null,
                            'npwp' => $value->npwp ?? null,
                            'no_telp' => $value->no_telp ?? null,
                            'status_perkawinan' => $value->status_perkawinan ?? null,
                            'nama_pasangan' => $value->nama_pasangan ?? null,
                            'tanggal_kuasa' => $value->tanggal_kuasa ?? null,
                            'hutang_pokok' => $value->hutang_pokok ?? null,
                            'nilai_jaminan' => $value->nilai_jaminan ?? null,
                            'hutang_barang' => $value->hutang_barang ?? null,
                            'merk' => $value->merk ?? null,
                            'tipe' => $value->tipe ?? null,
                            'tahun' => $value->tahun ?? null,
                            'warna' => $value->warna ?? null,
                            'no_rangka' => $value->no_rangka ?? null,
                            'no_mesin' => $value->no_mesin ?? null,
                            'nopol' => $value->nopol ?? null,
                            'pemilik_bpkb' => $value->pemilik_bpkb ?? null,
                            'nomor_bpkb' => $value->nomor_bpkb ?? null,
                            'customer_tipe' => $value->customer_tipe ?? null,
                            'tgl_awal_tenor' => $value->tgl_awal_tenor ?? null,
                            'tgl_akhir_tenor' => $value->tgl_akhir_tenor ?? null,
                            'type_produk' => $value->type_produk ?? null,
                            'cab' => $value->cab ?? null,
                            'rep' => $value->rep ?? null,
                            'kondisi' => $value->kondisi ?? null,
                        ];

                        // Persiapkan data contract status
                        $contractStatuses[] = [
                            'request_sertificate' => $data['data_request']['id'],
                            'status' => 'DRAFT',
                            'remarks' => 'Menunggu Proses Verifikasi',
                            'creator' => session('user_id'),
                        ];
                    }


                    // Insert massal data contracts
                    RequestCertificateContract::insert($contracts);

                    // Insert massal data contract statuses
                    RequestCertificateContractStatus::insert($contractStatuses);
                }

                if (cek_duplikasi_no_kontrak($data_no_kontrak)) {
                    $result['is_valid'] = true;
                } else {
                    $result['is_valid'] = false;
                    $result['message'] = "No. Kontrak tidak boleh sama";
                    DB::rollBack();
                    return $result;
                }

                if (cek_duplikasi_no_kontrak($data_no_kontrak)) {
                    $result['is_valid'] = true;
                } else {
                    $result['is_valid'] = false;
                    $result['message'] = "No. Kontrak tidak boleh sama";
                    DB::rollBack();
                    return $result;
                }

                //Insert Table Actor
                $actor = new Actor();
                $actor->users = session('user_id');
                $actor->content = $data_request->no_request . ' - ' . 'request_sertificate';
                $actor->action = 'UPDATED REQUEST CERTIFICATE ' . $data_request->no_request;
                $actor->save();
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


    public function confirm(Request $request)
    {
        $data = $request->all();
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {

            $actor = new Actor();
            $actor->users = session('user_id');
            $actor->content = json_encode($data);
            $actor->action = 'CONFIRMED QUOATATION ' . $data['no_document'];
            $actor->save();
            $actId = $actor->id;

            $docTrans = new DocumentTransaction();
            $docTrans->no_document = $data['no_document'];
            $docTrans->actors = $actId;
            $docTrans->state = 'CONFIRMED';
            $docTrans->save();

            $quo = Quotation::find($data['id']);
            $quo->status = 'CONFIRMED';
            $quo->save();

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            //throw $th;
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }
        return response()->json($result);
    }



    public function getDetailData($id)
    {
        DB::enableQueryLog();
        $datadb = RequestCertificate::with(['Creator', 'RequestContract', 'RequestContractStatus', 'RequestContract.DataWarkah', 'RequestContract.DataMinuta', 'RequestContract.RequestContractStatusDailyReport'])->where('id', $id);
        $data = $datadb->first();
        // dd($data->toArray());
        $query = DB::getQueryLog();
        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $data = $request->all();
        return view('web.certificate.modal.confirmdelete', $data);
    }


    public function submitWarkah(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {

            // dd($data['perjanjian_file'] != null);

            $dir = 'berkas/document/warkah/';
            $dir .= date('Y') . '/' . date('m');
            $pathlamp = public_path() . '/' . $dir . '/';
            $dbpathlamp = '/' . $dir . '/';
            // dd($data);
            $data_warkah = SertificateWarkah::where('request_sertificate_contract', $data['id'])->first();

            if ($data_warkah == null) {
                $data_warkah = new SertificateWarkah();
                $data_warkah->request_sertificate_contract = $data['id'];
            }

            if (isset($data['perjanjian_file']) && $data['perjanjian_file'] != null) {
                $perjanjian_file = $data['perjanjian_file'];
                $perjanjian_file_name = $data['perjanjian_file_name'];
                if (!File::isDirectory($pathlamp)) {
                    File::makeDirectory($pathlamp, 0777, true, true);
                }

                if ($data['perjanjian_file'] == '') {
                    uploadFileFromBlobString($pathlamp, $perjanjian_file_name, $perjanjian_file);
                } else {
                    if (strpos($perjanjian_file, 'base64,') !== false) {
                        $perjanjian_file = explode('base64,', $perjanjian_file)[1];
                    }
                    $decoded_file = base64_decode($perjanjian_file, true); // valid base64 returns false on failure
                    if ($decoded_file === false) {
                        throw new \Exception("Invalid base64 file string.");
                    }
                    File::put($pathlamp . $perjanjian_file_name, $decoded_file);
                }

                $data_warkah->perjanjian_pembiayaan_file = isset($perjanjian_file_name) ? $perjanjian_file_name : $data_warkah->perjanjian_pembiayaan_file;
                $data_warkah->perjanjian_pembiayaan_path = isset($dbpathlamp) ? $dbpathlamp : $data_warkah->perjanjian_pembiayaan_path;
            }
            if (isset($data['skmjf_file']) && $data['skmjf_file'] != null) {
                $skmjf_file = $data['skmjf_file'];
                $skmjf_file_name = $data['skmjf_file_name'];
                if (!File::isDirectory($pathlamp)) {
                    File::makeDirectory($pathlamp, 0777, true, true);
                }

                if ($data['skmjf_file'] == '') {
                    uploadFileFromBlobString($pathlamp, $skmjf_file_name, $skmjf_file);
                } else {
                    if (strpos($skmjf_file, 'base64,') !== false) {
                        $skmjf_file = explode('base64,', $skmjf_file)[1];
                    }
                    $decoded_file = base64_decode($skmjf_file, true); // valid base64 returns false on failure
                    if ($decoded_file === false) {
                        throw new \Exception("Invalid base64 file string.");
                    }
                    File::put($pathlamp . $skmjf_file_name, $decoded_file);
                }


                $data_warkah->skmjf_file = isset($skmjf_file_name) ? $skmjf_file_name : $data_warkah->skmjf_file;
                $data_warkah->skmjf_path = isset($dbpathlamp) ? $dbpathlamp : $data_warkah->skmjf_path;
            }
            if (isset($data['data_kendaraan_file']) && $data['data_kendaraan_file'] != null) {
                $data_kendaraan_file = $data['data_kendaraan_file'];
                $data_kendaraan_file_name = $data['data_kendaraan_file_name'];
                if (!File::isDirectory($pathlamp)) {
                    File::makeDirectory($pathlamp, 0777, true, true);
                }

                if ($data['data_kendaraan_file'] == '') {
                    uploadFileFromBlobString($pathlamp, $data_kendaraan_file_name, $data_kendaraan_file);
                } else {
                    if (strpos($data_kendaraan_file, 'base64,') !== false) {
                        $data_kendaraan_file = explode('base64,', $data_kendaraan_file)[1];
                    }
                    $decoded_file = base64_decode($data_kendaraan_file, true); // valid base64 returns false on failure
                    if ($decoded_file === false) {
                        throw new \Exception("Invalid base64 file string.");
                    }
                    File::put($pathlamp . $data_kendaraan_file_name, $decoded_file);
                }

                $data_warkah->data_kendaraan_file = isset($data_kendaraan_file_name) ? $data_kendaraan_file_name : $data_warkah->data_kendaraan_file;
                $data_warkah->data_kendaraan_path = isset($dbpathlamp) ? $dbpathlamp : $data_warkah->data_kendaraan_path;
            }
            if (isset($data['kk_file']) && $data['kk_file'] != null) {
                $kk_file = $data['kk_file'];
                $kk_file_name = $data['kk_file_name'];
                if (!File::isDirectory($pathlamp)) {
                    File::makeDirectory($pathlamp, 0777, true, true);
                }

                if ($data['kk_file'] == '') {
                    uploadFileFromBlobString($pathlamp, $kk_file_name, $kk_file);
                } else {
                    if (strpos($kk_file, 'base64,') !== false) {
                        $kk_file = explode('base64,', $kk_file)[1];
                    }
                    $decoded_file = base64_decode($kk_file, true); // valid base64 returns false on failure
                    if ($decoded_file === false) {
                        throw new \Exception("Invalid base64 file string.");
                    }
                    File::put($pathlamp . $kk_file_name, $decoded_file);
                }

                $data_warkah->kk_file = isset($kk_file_name) ? $kk_file_name : $data_warkah->kk_file;
                $data_warkah->kk_path = isset($dbpathlamp) ? $dbpathlamp : $data_warkah->kk_path;
            }
            if (isset($data['ktp_bpkb_file']) && $data['ktp_bpkb_file'] != null) {
                $ktp_bpkb_file = $data['ktp_bpkb_file'];
                $ktp_bpkb_file_name = $data['ktp_bpkb_file_name'];
                if (!File::isDirectory($pathlamp)) {
                    File::makeDirectory($pathlamp, 0777, true, true);
                }

                if ($data['ktp_bpkb_file'] == '') {
                    uploadFileFromBlobString($pathlamp, $ktp_bpkb_file_name, $ktp_bpkb_file);
                } else {
                    if (strpos($ktp_bpkb_file, 'base64,') !== false) {
                        $ktp_bpkb_file = explode('base64,', $ktp_bpkb_file)[1];
                    }
                    $decoded_file = base64_decode($ktp_bpkb_file, true); // valid base64 returns false on failure
                    if ($decoded_file === false) {
                        throw new \Exception("Invalid base64 file string.");
                    }
                    File::put($pathlamp . $ktp_bpkb_file_name, $decoded_file);
                }

                $data_warkah->ktp_bpkb_file = isset($ktp_bpkb_file_name) ? $ktp_bpkb_file_name : $data_warkah->ktp_bpkb_file;
                $data_warkah->ktp_bpkb_path = isset($dbpathlamp) ? $dbpathlamp : $data_warkah->ktp_bpkb_path;
            }
            if (isset($data['ktp_debitur_file']) && $data['ktp_debitur_file'] != null) {
                $ktp_debitur_file = $data['ktp_debitur_file'];
                $ktp_debitur_file_name = $data['ktp_debitur_file_name'];
                if (!File::isDirectory($pathlamp)) {
                    File::makeDirectory($pathlamp, 0777, true, true);
                }

                if ($data['ktp_debitur_file'] == '') {
                    uploadFileFromBlobString($pathlamp, $ktp_debitur_file_name, $ktp_debitur_file);
                } else {
                    if (strpos($ktp_debitur_file, 'base64,') !== false) {
                        $ktp_debitur_file = explode('base64,', $ktp_debitur_file)[1];
                    }
                    $decoded_file = base64_decode($ktp_debitur_file, true); // valid base64 returns false on failure
                    if ($decoded_file === false) {
                        throw new \Exception("Invalid base64 file string.");
                    }
                    File::put($pathlamp . $ktp_debitur_file_name, $decoded_file);
                }

                $data_warkah->ktp_debitur_file = isset($ktp_debitur_file_name) ? $ktp_debitur_file_name : $data_warkah->ktp_debitur_file;
                $data_warkah->ktp_debitur_path = isset($dbpathlamp) ? $dbpathlamp : $data_warkah->ktp_debitur_path;
            }
            if (isset($data['form_perjanjian_nama_bpkb_file']) && $data['form_perjanjian_nama_bpkb_file'] != null) {
                $form_perjanjian_nama_bpkb_file = $data['form_perjanjian_nama_bpkb_file'];
                $form_perjanjian_nama_bpkb_file_name = $data['form_perjanjian_nama_bpkb_file_name'];
                if (!File::isDirectory($pathlamp)) {
                    File::makeDirectory($pathlamp, 0777, true, true);
                }

                if ($data['form_perjanjian_nama_bpkb_file'] == '') {
                    uploadFileFromBlobString($pathlamp, $form_perjanjian_nama_bpkb_file_name, $form_perjanjian_nama_bpkb_file);
                } else {
                    if (strpos($form_perjanjian_nama_bpkb_file, 'base64,') !== false) {
                        $form_perjanjian_nama_bpkb_file = explode('base64,', $form_perjanjian_nama_bpkb_file)[1];
                    }
                    $decoded_file = base64_decode($form_perjanjian_nama_bpkb_file, true); // valid base64 returns false on failure
                    if ($decoded_file === false) {
                        throw new \Exception("Invalid base64 file string.");
                    }
                    File::put($pathlamp . $form_perjanjian_nama_bpkb_file_name, $decoded_file);
                }

                $data_warkah->form_perjanjian_nama_bpkb_file = isset($form_perjanjian_nama_bpkb_file_name) ? $form_perjanjian_nama_bpkb_file_name : $data_warkah->form_perjanjian_nama_bpkb_file;
                $data_warkah->form_perjanjian_nama_bpkb_path = isset($dbpathlamp) ? $dbpathlamp : $data_warkah->form_perjanjian_nama_bpkb_path;
            }
            if (isset($data['ktp_pasangan_nama_bpkp_file']) && $data['ktp_pasangan_nama_bpkp_file'] != null) {
                $ktp_pasangan_nama_bpkp_file = $data['ktp_pasangan_nama_bpkp_file'];
                $ktp_pasangan_nama_bpkp_file_name = $data['ktp_pasangan_nama_bpkp_file_name'];
                if (!File::isDirectory($pathlamp)) {
                    File::makeDirectory($pathlamp, 0777, true, true);
                }

                if ($data['ktp_pasangan_nama_bpkp_file'] == '') {
                    uploadFileFromBlobString($pathlamp, $ktp_pasangan_nama_bpkp_file_name, $ktp_pasangan_nama_bpkp_file);
                } else {
                    if (strpos($ktp_pasangan_nama_bpkp_file, 'base64,') !== false) {
                        $ktp_pasangan_nama_bpkp_file = explode('base64,', $ktp_pasangan_nama_bpkp_file)[1];
                    }
                    $decoded_file = base64_decode($ktp_pasangan_nama_bpkp_file, true); // valid base64 returns false on failure
                    if ($decoded_file === false) {
                        throw new \Exception("Invalid base64 file string.");
                    }
                    File::put($pathlamp . $ktp_pasangan_nama_bpkp_file_name, $decoded_file);
                }

                $data_warkah->ktp_pasangan_nama_bpkp_file = isset($ktp_pasangan_nama_bpkp_file_name) ? $ktp_pasangan_nama_bpkp_file_name : $data_warkah->ktp_pasangan_nama_bpkp_file;
                $data_warkah->ktp_pasangan_nama_bpkp_path = isset($dbpathlamp) ? $dbpathlamp : $data_warkah->ktp_pasangan_nama_bpkp_path;
            }
            if (isset($data['ktp_pasangan_debitur_file']) && $data['ktp_pasangan_debitur_file'] != null) {
                $ktp_pasangan_debitur_file = $data['ktp_pasangan_debitur_file'];
                $ktp_pasangan_debitur_file_name = $data['ktp_pasangan_debitur_file_name'];
                if (!File::isDirectory($pathlamp)) {
                    File::makeDirectory($pathlamp, 0777, true, true);
                }

                if ($data['ktp_pasangan_debitur_file'] == '') {
                    uploadFileFromBlobString($pathlamp, $ktp_pasangan_debitur_file_name, $ktp_pasangan_debitur_file);
                } else {
                    if (strpos($ktp_pasangan_debitur_file, 'base64,') !== false) {
                        $ktp_pasangan_debitur_file = explode('base64,', $ktp_pasangan_debitur_file)[1];
                    }
                    $decoded_file = base64_decode($ktp_pasangan_debitur_file, true); // valid base64 returns false on failure
                    if ($decoded_file === false) {
                        throw new \Exception("Invalid base64 file string.");
                    }
                    File::put($pathlamp . $ktp_pasangan_debitur_file_name, $decoded_file);
                }

                $data_warkah->ktp_pasangan_debitur_file = isset($ktp_pasangan_debitur_file_name) ? $ktp_pasangan_debitur_file_name : $data_warkah->ktp_pasangan_debitur_file;
                $data_warkah->ktp_pasangan_debitur_path = isset($dbpathlamp) ? $dbpathlamp : $data_warkah->ktp_pasangan_debitur_path;
            }
            $data_warkah->creator = session('user_id');
            $data_warkah->save();

            // Insert Actor
            $data_contract = RequestCertificateContract::find($data['id']);
            if (isset($data['perjanjian_file']) && $data['perjanjian_file'] != null) {
                $this->insertActorFileWarkah('PERJANJIAN', $data_contract->contract_number);
            }
            if (isset($data['skmjf_file']) && $data['skmjf_file'] != null) {
                $this->insertActorFileWarkah('SKJMF FILE', $data_contract->contract_number);
            }
            if (isset($data['data_kendaraan_file']) && $data['data_kendaraan_file'] != null) {
                $this->insertActorFileWarkah('DATA KENDARAAN', $data_contract->contract_number);
            }
            if (isset($data['kk_file']) && $data['kk_file'] != null) {
                $this->insertActorFileWarkah('KK ', $data_contract->contract_number);
            }
            if (isset($data['ktp_bpkb_file']) && $data['ktp_bpkb_file'] != null) {
                $this->insertActorFileWarkah('KTP BPKB', $data_contract->contract_number);
            }
            if (isset($data['ktp_debitur_file']) && $data['ktp_debitur_file'] != null) {
                $this->insertActorFileWarkah('KTP DEBITUR', $data_contract->contract_number);
            }
            if (isset($data['form_perjanjian_nama_bpkb_file']) && $data['form_perjanjian_nama_bpkb_file'] != null) {
                $this->insertActorFileWarkah('FORM PERJANJIAN NAMA BPKB', $data_contract->contract_number);
            }
            if (isset($data['ktp_pasangan_nama_bpkp_file']) && $data['ktp_pasangan_nama_bpkp_file'] != null) {
                $this->insertActorFileWarkah('KTP PASANGAN NAMA BPKP', $data_contract->contract_number);
            }
            if (isset($data['ktp_pasangan_debitur_file']) && $data['ktp_pasangan_debitur_file'] != null) {
                $this->insertActorFileWarkah('KTP PASANGAN DEBITUR', $data_contract->contract_number);
            }

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }
        return response()->json($result);
    }
    public function updateStatusKontrak(Request $request)
    {
        $data = $request->all();

        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            $data_contract = RequestCertificateContract::find($data['id_kontrak']);
            $data_contract->status = 'COMPLETE';
            $data_contract->updated_at = date('Y-m-d H:i:s');
            $data_contract->save();

            $id_req_ser = $data_contract->request_sertificate;
            // Insert Table Contract Status
            $data_contract_status = new RequestCertificateContractStatus();
            $data_contract_status->request_sertificate = $id_req_ser;
            $data_contract_status->request_sertificate_contract = $data_contract->id;
            $data_contract_status->creator =  session('user_id');
            $data_contract_status->status = "COMPLETE";
            $data_contract_status->remarks = "Warkah  telah diupload pada Permintaan SK dengan No. " . $data_contract->contract_number . "";
            $data_contract_status->save();

            $cek_status = cekStatusRequest($id_req_ser);

            // update table request_sertificate
            $data_sertificate = RequestCertificate::find($id_req_ser);
            $data_sertificate->status = $cek_status;
            $data_sertificate->updated_at = date('Y-m-d H:i:s');
            $data_sertificate->save();


            //Insert Table Actor
            $actor = new Actor();
            $actor->users = session('user_id');
            $actor->content = null;
            $actor->action = "Warkah telah diupload dan dikonfirmasi pada Permintaan SK dengan No. Kontrak" . $data_contract->contract_number . "";
            $actor->save();


            //Insert Table Actor
            $actor = new Actor();
            $actor->users = session('user_id');
            $actor->content = session('user_name') . 'Telah melengkapi file Warkah pada no kontrak ' . $data['id_kontrak'];
            $actor->action = "MELENGKAPI FILE WARKAH PADA NO KONTRAK " . $data['id_kontrak'];
            $actor->save();

            // Send Notification
            $data_company = Karyawan::with('CompanyKaryawan')->where('company', 3)->first();


            if (env('FONTE_API_KEY') != null) {
                if (!empty($data_company)) {
                    $nama = $data_company->nama_lengkap;
                    $message = "Transaksi baru untuk " . $nama . " baru saja dibuat Warkah \nPada No.Kontrak " . $data_contract->contract_number . "\ntolong segera lakukan HIT!";
                    $no_hp = $data_company->CompanyKaryawan->no_hp;
                    sendFonteNotification($no_hp, $message);
                }
            }

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
            DB::rollBack();
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
                ->whereNotNull('delegate_to')
                ->where('request_sertificate', $data['no_request'])
                ->where('status', 'COMPLETE')
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
                'PemberiFidusia' => 'PemberiFidusia',
                'JenisKelamin' => 'JenisKelamin',
                'TempatLahir' => 'TempatLahir',
                'TanggalLahir' => 'TanggalLahir',
                'Pekerjaan' => 'Pekerjaan',
                'Alamat' => 'Alamat',
                'RT' => 'RT',
                'RW' => 'RW',
                'Kelurahan' => 'Kelurahan',
                'Kecamatan' => 'Kecamatan',
                'Kabupaten' => 'Kabupaten',
                'Provinsi' => 'Provinsi',
                'KodePos' => 'KodePos',
                'KTP' => 'KTP',
                'NPWP' => 'NPWP',
                'NoTelp' => 'NoTelp',
                'StatusPerkawinan' => 'StatusPerkawinan',
                'NamaPasangan' => 'NamaPasangan',
                'TanggalKuasa' => 'TanggalKuasa',
                'No_Perjanjian_Kontrak' => 'No_Perjanjian_Kontrak',
                'NamaDebitur' => 'NamaDebitur',
                'HutangPokok' => 'HutangPokok',
                'NilaiJaminan' => 'NilaiJaminan',
                'NilaiBarang' => 'NilaiBarang',
                'Merk' => 'Merk',
                'Tipe' => 'Tipe',
                'Tahun' => 'Tahun',
                'Warna' => 'Warna',
                'NomorRangka' => 'NomorRangka',
                'NomorMesin' => 'NomorMesin',
                'NomorPolisi' => 'NomorPolisi',
                'PemilikBPKB' => 'PemilikBPKB',
                'NomorBPKB' => 'NomorBPKB',
                'CustomerType' => 'CustomerType',
                'TanggalAwalTenor' => 'TanggalAwalTenor',
                'TanggalAkhirTenor' => 'TanggalAkhirTenor',
                'TypeProduk' => 'TypeProduk',
                'NoKontrak' => 'NoKontrak',
                'Cab' => 'Cab',
                'Rep' => 'Rep',
                'Kondisi' => 'Kondisi',
                'NomorAkta' => 'NomorAkta',
                'Tanggal Akta' => 'Tanggal Akta',
                'NO SERTIFIKAT' => 'NO SERTIFIKAT',
                'TGL SERTIFIKAT' => 'TGL SERTIFIKAT',
                'Biaya Jasa Akta' => 'Biaya Jasa Akta',
                'Biaya PNBP' => 'Biaya PNBP'
            ];

            $detail_get = [];
            // dd($data);
            foreach ($_data as $key => $row) {
                $ktp = empty($row['ktp']) ? '-' : "\t" . $row['ktp'];
                $no_telp = empty($row['no_telp']) ? '-' : "\t" . $row['no_telp'];
                $NoKontrak = empty($row['contract_number']) ? '-' : $row['contract_number'];
                $tgl_serti = empty($row['tanggal_sertifikat']) ? '-' : $row['tanggal_sertifikat'];


                $biayaJasaAkta = '51000';


                $detail_get[] = [
                    'PemberiFidusia' => $row['pemberi_fidusia'],
                    'JenisKelamin' => $row['jenis_kelamin'],
                    'TempatLahir' => $row['tempat_lahir'],
                    'TanggalLahir' => $row['tanggal_lahir'],
                    'Pekerjaan' => $row['pekerjaan'],
                    'Alamat' => $row['alamat'],
                    'RT' => $row['rt'],
                    'RW' => $row['rw'],
                    'Kelurahan' => $row['kelurahan'],
                    'Kecamatan' => $row['kecamatan'],
                    'Kabupaten' => $row['kabupaten'],
                    'Provinsi' => $row['provinsi'],
                    'KodePos' => $row['kode_pos'],
                    'KTP' => $ktp,
                    'NPWP' => $row['npwp'],
                    'NoTelp' => $no_telp,
                    'StatusPerkawinan' => $row['status_perkawinan'],
                    'NamaPasangan' => $row['nama_pasangan'],
                    'TanggalKuasa' => $row['tanggal_kuasa'],
                    'No_Perjanjian_Kontrak' => $NoKontrak,
                    'NamaDebitur' => $row['debitur'],
                    'HutangPokok' => $row['hutang_pokok'],
                    'NilaiJaminan' => $row['nilai_jaminan'],
                    'NilaiBarang' => $row['hutang_barang'],
                    'Merk' => $row['merk'],
                    'Tipe' => $row['tipe'],
                    'Tahun' => $row['tahun'],
                    'Warna' => $row['warna'],
                    'NomorRangka' => $row['no_rangka'],
                    'NomorMesin' => $row['no_mesin'],
                    'NomorPolisi' => $row['nopol'],
                    'PemilikBPKB' => $row['pemilik_bpkb'],
                    'NomorBPKB' => $row['nomor_bpkb'],
                    'CustomerType' => $row['customer_tipe'],
                    'TanggalAwalTenor' => $row['tgl_awal_tenor'],
                    'TanggalAkhirTenor' => $row['tgl_akhir_tenor'],
                    'TypeProduk' => $row['type_produk'],
                    'NoKontrak' => $NoKontrak,
                    'Cab' => $row['cab'],
                    'Rep' => $row['rep'],
                    'Kondisi' => $row['kondisi'],
                    'NomorAkta' => $row['seq_number'],
                    'Tanggal Akta' => getTanggal($row['waktu_tgl_notaris']),
                    'NO SERTIFIKAT' => $row['no_sk'],
                    'TGL SERTIFIKAT' => $tgl_serti,
                    'Biaya Jasa Akta' => $biayaJasaAkta,
                    'Biaya PNBP' => $row['biaya_pnbp'],
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

    private function insertActorFileWarkah($nama_warkah, $no_kontrak)
    {
        //Insert Table Actor
        $actor = new Actor();
        $actor->users = session('user_id');
        $actor->content = session('user_name') . 'Telah menambahkan file Warkah ' . $nama_warkah . ' pada no kontrak ' . $no_kontrak;
        $actor->action = "MENAMBHAKAN FILE WARKAH " . $nama_warkah . " PADA NO KONTRAK " . $no_kontrak;
        $actor->save();

        return true;
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
