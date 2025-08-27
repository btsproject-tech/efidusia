<?php

namespace App\Http\Controllers\api\report;

use App\SertificateMinuta;
use App\RequestCertificate;
use App\Models\Master\Actor;
use Illuminate\Http\Request;
use App\Models\Master\Document;
use App\Models\Transaksi\Invoice;
use Illuminate\Support\Facades\DB;
use App\RequestCertificateContract;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\Transaksi\InvoiceItem;
use App\Models\Transaksi\InvoicingModel;
use App\RequestCertificateContractStatus;
use App\Models\Master\DocumentTransaction;
use App\Models\Transaksi\InvoicingItemModel;

class ReportDailyController extends Controller
{
    public function getTableName()
    {
        return "request_sertificate_contract";
    }

    public function getData(Request $request)
    {
        // dd(session()->all());
        $data = $request->all();
        $date_now = date('Y-m-d');
        DB::enableQueryLog();
        $data['data'] = [];
        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $tgl_awal = empty($data['tgl_awal']) ? $date_now : $data['tgl_awal'];
        $tgl_akhir = empty($data['tgl_akhir']) ? $date_now : $data['tgl_akhir'];

        // dd($tgl_awal, $tgl_akhir);

        $datadb = RequestCertificateContract::with(['RequestContract', 'RequestContractStatusDailyReport', 'RequestContract.Creator.Karyawan', 'DataMinuta'])
            ->whereNull('deleted')
            ->whereHas('RequestContract', function ($query) {
                $query->whereNull('deleted');
            })
            // ->whereDate('created_at', '>=', $tgl_awal)
            // ->whereDate('created_at', '<=', $tgl_akhir)
            ->orderBy('id', 'desc');

        if (session('akses') == 'notaris') {
            $datadb->whereHas('RequestContract', function ($query) {
                $query->where('delegate_to', session('nik'));
            });
        }

        if (session('akses') == 'vendor') {
            $datadb->where(function ($query) {
                $query->where('updater', session('user_id'));
            });
        }

        if (session('akses') == 'finance') {
            $datadb->where(function ($query) {
                $query->where('creator', session('user_id'));
            });
        }

        // if (isset($data['tgl_awal'])) {
        //     if ($data['tgl_awal'] != '') {
        //         $datadb->whereHas('RequestContract', function ($query) use ($data) {
        //             return $query->where('date_request', '>=', $data['tgl_awal'])
        //                 ->where('date_request', '<=', $data['tgl_akhir']);
        //         });
        //     }
        // }

        if (isset($_POST)) {
            $data['recordsTotal'] = $datadb->get()->count();
            if (isset($_POST['search']['value'])) {
                $keyword = $_POST['search']['value'];
                $datadb->where(function ($query) use ($keyword) {
                    $query->where('debitur', 'LIKE', '%' . $keyword . '%');
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
        // dd(session('akses'), session('user_id'));
        // dd($data);
        // print_r($query);die;
        return json_encode($data);
    }

    public function exportExcel(Request $request)
    {
        $data = $request->all();

        // $date_now = date('Y-m-d');
        // $tgl_awal = empty($data['tgl_awal']) ? $date_now : $data['tgl_awal'];
        // $tgl_akhir = empty($data['tgl_akhir']) ? $date_now : $data['tgl_akhir'];
        $datadb = RequestCertificateContract::with(['RequestContract', 'RequestContractStatusDailyReport', 'RequestContract.Creator.Karyawan', 'DataMinuta'])
            ->whereNull('deleted')
            ->whereNotNull('seq_number')
            // ->whereDate('created_at', '>=', $tgl_awal)
            // ->whereDate('created_at', '<=', $tgl_akhir)
            ->orderBy('id', 'desc');

        if (session('akses') == 'notaris') {
            $datadb->whereHas('RequestContract', function ($query) {
                $query->where('delegate_to', session('nik'));
            });
        }

        // if (session('akses') == 'vendor') {
        //     $datadb->where(function ($query) {
        //         $query->where('creator', session('user_id'));
        //     });
        // }

        if (session('akses') == 'finance') {
            $datadb->where(function ($query) {
                $query->where('creator', session('user_id'));
            });
        }

        if (isset($data['tgl_awal'])) {
            if ($data['tgl_awal'] != '') {
                $datadb->whereHas('RequestContract', function ($query) use ($data) {
                    return $query->whereDate('created_at', '>=', $data['tgl_awal'])
                        ->whereDate('created_at', '<=', $data['tgl_akhir']);
                });
            }
        }

        $data = $datadb->get()->toArray();

        // dd($data);

        $query = DB::getQueryLog();

        $csvHeader = ['PemberiFidusia', 'JenisKelamin', 'TempatLahir', 'TanggalLahir', 'Pekerjaan', 'Alamat', 'RT', 'RW', 'Kelurahan', 'Kecamatan', 'Kabupaten', 'Provinsi', 'KodePos', 'KTP', 'NPWP', 'NoTelp', 'StatusPerkawinan', 'NamaPasangan', 'TanggalKuasa', 'No_Perjanjian_Kontrak', 'NamaDebitur', 'HutangPokok', 'NilaiJaminan', 'NilaiBarang', 'Merk', 'Tipe', 'Tahun', 'Warna', 'NomorRangka', 'NomorMesin', 'NomorPolisi', 'PemilikBPKB', 'NomorBPKB', 'CustomerType', 'TanggalAwalTenor', 'TanggalAkhirTenor', 'TypeProduk', 'NoKontrak', 'Cab', 'Rep', 'Kondisi', 'NomorAkta', 'Tanggal Akta', 'NO SERTIFIKAT', 'TGL SERTIFIKAT', 'Biaya Jasa Akta', 'Biaya PNBP'];

        $fileName = 'MAS_' . date('Ymd_His') . '.csv';
        $dir = 'berkas/document/export/excel/';
        $filePath = public_path('/' . $dir);
        if (!File::isDirectory($filePath)) {
            File::makeDirectory($filePath, 0777, true, true);
        }

        $filePath = $filePath . $fileName;

        $file = fopen($filePath, 'w');

        fputcsv($file, $csvHeader);

        foreach ($data as $key => $row) {
            // $pemeberiFidusia = empty($row['pemberi_fidusia']) ?? '-';

            $ktp = empty($row['ktp']) ? '-' : "\t" . $row['ktp'];
            $no_telp = empty($row['no_telp']) ? '-' : "\t" . $row['no_telp'];
            $NoKontrak = empty($row['no_kontrak']) ? '-' : $row['no_kontrak'];
            $tgl_serti = empty($row['tanggal_sertifikat']) ? '-' : $row['tanggal_sertifikat'];
            $no_serti = empty($row['no_sk']) ? '-' : $row['no_sk'];
            $biayaJasaAkta = '51000';
            $nilaiBarang = cari_biaya_barang($row['hutang_barang']);

            // dd(array_sum($nilaiBarang));

            fputcsv($file, [$row['pemberi_fidusia'], $row['jenis_kelamin'], $row['tempat_lahir'], $row['tanggal_lahir'], $row['pekerjaan'], $row['alamat'], $row['rt'], $row['rw'], $row['kelurahan'], $row['kecamatan'], $row['kabupaten'], $row['provinsi'], $row['kode_pos'], $ktp, $row['npwp'], $no_telp, $row['status_perkawinan'], $row['nama_pasangan'], $row['tanggal_kuasa'], $row['contract_number'], $row['debitur'], $row['hutang_pokok'], $row['nilai_jaminan'], $row['hutang_barang'], $row['merk'], $row['tipe'], $row['tahun'], $row['warna'], $row['no_rangka'], $row['no_mesin'], $row['nopol'], $row['pemilik_bpkb'], $row['nomor_bpkb'], $row['customer_tipe'], $row['tgl_awal_tenor'], $row['tgl_akhir_tenor'], $row['type_produk'], $NoKontrak, $row['cab'], $row['rep'], $row['kondisi'], $row['seq_number'], $row['type_produk'], $no_serti, $tgl_serti, $biayaJasaAkta, $nilaiBarang]);
        }

        fclose($file);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
