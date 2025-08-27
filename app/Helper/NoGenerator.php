<?php

use App\Models\Master\ActivityLog;
use App\Models\Master\Actor;
use App\models\master\MasterDepartemen;
use App\Models\Master\PricePNBP;
use App\Models\Own\ProdukSatuan;
use App\RequestCertificate;
use App\SertificateWarkah;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;

function digit_count($length, $value)
{
    while (strlen($value) < $length)
        $value = '0' . $value;
    return $value;
}

function generateNoDocument()
{
    $no = 'DOC' . date('y') . strtoupper(date('M'));
    $data = DB::table('document')->where('no_document', 'LIKE', '%' . $no . '%')->orderBy('no_document', 'desc')->get()->toArray();

    $seq = 1;
    if (!empty($data)) {
        $data = current($data);
        $seq = str_replace($no, '', $data->no_document);
        $seq = intval($seq) + 1;
    }

    $seq = digit_count(4, $seq);
    $no .= $seq;
    return $no;
}
function generateNoSertifikat()
{
    $no = 'DOC' . date('y') . strtoupper(date('M'));
    $data = DB::table('request_sertificate')->where('no_request', 'LIKE', '%' . $no . '%')->orderBy('no_request', 'desc')->get()->toArray();

    $seq = 1;
    if (!empty($data)) {
        $data = current($data);
        $seq = str_replace($no, '', $data->no_request);
        $seq = intval($seq) + 1;
    }

    $seq = digit_count(4, $seq);
    $no .= $seq;
    return $no;
}

function generateNoQuotation()
{
    $no = 'QUO' . date('y') . strtoupper(date('m')) . date('d');
    $data = DB::table('quotation')->where('no_quotation', 'LIKE', '%' . $no . '%')->orderBy('no_quotation', 'desc')->get()->toArray();

    $seq = 1;
    if (!empty($data)) {
        $data = current($data);
        $seq = str_replace($no, '', $data->no_quotation);
        $seq = intval($seq) + 1;
    }

    $seq = digit_count(3, $seq);
    $no .= $seq;
    return $no;
}

function generateNoSi()
{
    $no = 'SI' . date('y') . strtoupper(date('m'));
    $data = DB::table('shipping_instruction')->where('no_shipping', 'LIKE', '%' . $no . '%')->orderBy('no_shipping', 'desc')->get()->toArray();

    $seq = 1;
    if (!empty($data)) {
        $data = current($data);
        $seq = str_replace($no, '', $data->no_shipping);
        $seq = intval($seq) + 1;
    }

    $seq = digit_count(3, $seq);
    $no .= $seq;
    return $no;
}

function generateNoJob()
{
    $no = 'JOB' . date('y') . strtoupper(date('m'));
    $data = DB::table('shipping_excecution')->where('job_number', 'LIKE', '%' . $no . '%')->orderBy('job_number', 'desc')->get()->toArray();

    $seq = 1;
    if (!empty($data)) {
        $data = current($data);
        $seq = str_replace($no, '', $data->job_number);
        $seq = intval($seq) + 1;
    }

    $seq = digit_count(3, $seq);
    $no .= $seq;
    return $no;
}

function generateNoInvoice()
{
    $no = 'INV' . date('y') . strtoupper(date('m'));
    $data = DB::table('invoice')->where('no_invoice', 'LIKE', '%' . $no . '%')->orderBy('no_invoice', 'desc')->get()->toArray();

    $seq = 1;
    if (!empty($data)) {
        $data = current($data);
        $seq = str_replace($no, '', $data->no_invoice);
        $seq = intval($seq) + 1;
    }

    $seq = digit_count(3, $seq);
    $no .= $seq;
    return $no;
}

function generateNoManifest()
{
    $no = 'MAN' . date('y') . strtoupper(date('m'));
    $data = DB::table('manifest')->where('no_manifest', 'LIKE', '%' . $no . '%')->orderBy('no_manifest', 'desc')->get()->toArray();

    $seq = 1;
    if (!empty($data)) {
        $data = current($data);
        $seq = str_replace($no, '', $data->no_manifest);
        $seq = intval($seq) + 1;
    }

    $seq = digit_count(3, $seq);
    $no .= $seq;
    return $no;
}

function generateNoInvoicing()
{
    // $no = 'INV/II/' . strtoupper(date('m')) . date('y');
    $no = 'INV/II/' . date('Y') . '/';
    $data = DB::table('invoicing')->where('no_invoice', 'LIKE', '%' . $no . '%')->orderBy('no_invoice', 'desc')->get()->toArray();

    $seq = 1;
    if (!empty($data)) {
        $data = current($data);
        $seq = str_replace($no, '', $data->no_invoice);
        $seq = intval($seq) + 1;
    }

    // $seq = digit_count(4, $seq);
    $no .= $seq;
    // dd($no);
    return $no;
}

function cekStatusRequest($id_req)
{
    $data_status = [];
    $data_req = RequestCertificate::with(['RequestContract'])->find($id_req);

    foreach ($data_req->RequestContract as $key => $value) {
        $data_status[] = $value->status;
    }

    $statusCount = [];
    foreach ($data_status as $status) {
        $statusCount[$status] = isset($statusCount[$status]) ? $statusCount[$status] + 1 : 1;
    }
    // dd($data_status);
    if (in_array('ON PROCESS', $data_status)) {
        return "ON PROCESS";
    }
    // Check for DRAFT status and != DRAFT
    if (in_array('DRAFT', $data_status) && array_diff($data_status, ['DRAFT'])) {
        return "ON PROCESS";
    }
    // jika masih ada status APPROVE maka buat APPROVE saja
    if (in_array('APPROVE', $data_status)) {
        return "ON PROCESS";
    }
    // jika masih ada status DONE maka buat DONE saja
    if (in_array('DONE', $data_status)) {
        return "DONE";
    }
    // jika masih ada status COMPLETE maka buat COMPLETE saja
    if (in_array('COMPLETE', $data_status)) {
        return "COMPLETE";
    }

    $mostFrequentStatus = '';
    $maxCount = 0;
    foreach ($statusCount as $status => $count) {
        if ($count > $maxCount) {
            $maxCount = $count;
            $mostFrequentStatus = $status;
        }
    }
    // dd($statusCount);
    // Periksa apakah semua jumlah status sama
    $uniqueCounts = array_unique(array_values($statusCount));
    // dd($mostFrequentStatus);
    if (count($uniqueCounts) == 1) {
        return $mostFrequentStatus;
    }

    // dd($mostFrequentStatus);
    return $mostFrequentStatus;
}

function generateNoPayment()
{
    $no = 'PAY' . strtoupper(date('m')) . date('y');
    $data = DB::table('payment_invoice')->where('no_payment', 'LIKE', '%' . $no . '%')->orderBy('no_payment', 'desc')->get()->toArray();

    $seq = 1;
    if (!empty($data)) {
        $data = current($data);
        $seq = str_replace($no, '', $data->no_payment);
        $seq = intval($seq) + 1;
    }

    $seq = digit_count(4, $seq);
    $no .= $seq;
    return $no;
}


function sendFonteNotification($phoneNumber, $message)
{
    $client = new Client();
    $apiKey = env('FONTE_API_KEY');
    $is_valid = false;
    $pesan = '';
    // dd($apiKey);
    try {
        $response = $client->post('https://api.fonnte.com/send', [
            'headers' => [
                'Authorization' => $apiKey,
            ],
            'form_params' => [
                'target' => $phoneNumber,
                'message' => $message,
                'countryCode' => '62',
            ]
        ]);

        $status = json_decode($response->getBody(), true);
        Log::info('Fonnte API Response: ' . json_encode($status));

        if (!$status['status']) {
            throw new \Exception('Failed to send WhatsApp message.');
        }
        // insert ACTOR
        $actor = new Actor();
        $actor->users = session('user_id');
        $actor->content =  $message;
        $actor->action = "Mengirimkan notifikasi ke nomor : " . $phoneNumber;
        $actor->save();

        // return dd($actor);

        $is_valid = true;
    } catch (\Exception $e) {
        $pesan = $e->getMessage();
        throw new \Exception('Fonnte API error: ' . $e->getMessage());
    }

    if ($is_valid == false) {
        custom_error_log('SEND NOTIFICATION TO NUMBER ' . $phoneNumber, '', $pesan);
    }
    return $is_valid;
}


function cari_biaya_barang($nilai_barang)
{
    // Contoh data array yang diberikan
    $data_biaya = PricePNBP::get()->toArray();


    // Lakukan pencarian
    foreach ($data_biaya as $row) {
        if ($nilai_barang >= $row['batas_bawah'] && $nilai_barang <= $row['batas_atas']) {
            return $row['biaya'];
        }
    }

    // Jika tidak ditemukan data yang cocok
    return "Nilai barang tidak ditemukan dalam rentang yang ada.";
}

function get_list_status_verification($status)
{
    $data_status_verifikasi = ['ON PROCCESS', 'VERIFIED', 'DONE', 'COMPLETE', 'FINISHED'];
    $data = [];
    if ($status == 'VERIFIED') {
        unset($data_status_verifikasi[0]);
        unset($data_status_verifikasi[1]);
    } elseif ($status == 'DONE') {
        unset($data_status_verifikasi[0]);
        unset($data_status_verifikasi[1]);
        unset($data_status_verifikasi[2]);
    } elseif ($status == 'COMPLETE') {
        unset($data_status_verifikasi[0]);
        unset($data_status_verifikasi[1]);
        unset($data_status_verifikasi[2]);
        unset($data_status_verifikasi[3]);
    } elseif ($status == 'FINISHED') {
        unset($data_status_verifikasi[0]);
        unset($data_status_verifikasi[1]);
        unset($data_status_verifikasi[2]);
        unset($data_status_verifikasi[3]);
        unset($data_status_verifikasi[4]);
    }
    foreach ($data_status_verifikasi as $key => $value) {
        $data[$key] = '
                <li class="event-list">
                    <div class="event-timeline-dot">
                        <i class="bx bx-right-arrow-circle font-size-18"></i>
                    </div>
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <span class="badge bg-secondary" style="font-size:12px;"> ' . $value . '</span>
                        </div>
                    </div>
                </li>
        ';
    }

    return $data;
}


function getListDashboard($status)
{
    $data_status_verifikasi = ['ON PROCCESS', 'VERIFIED', 'DONE', 'COMPLETE', 'FINISHED'];
    $data = [];
    if ($status == 'VERIFIED') {
        unset($data_status_verifikasi[0]);
        unset($data_status_verifikasi[1]);
    } elseif ($status == 'DONE') {
        unset($data_status_verifikasi[0]);
        unset($data_status_verifikasi[1]);
        unset($data_status_verifikasi[2]);
    } elseif ($status == 'COMPLETE') {
        unset($data_status_verifikasi[0]);
        unset($data_status_verifikasi[1]);
        unset($data_status_verifikasi[2]);
        unset($data_status_verifikasi[3]);
    } elseif ($status == 'FINISHED') {
        unset($data_status_verifikasi[0]);
        unset($data_status_verifikasi[1]);
        unset($data_status_verifikasi[2]);
        unset($data_status_verifikasi[3]);
        unset($data_status_verifikasi[4]);
    }

    foreach ($data_status_verifikasi as $key => $value) {
        $data[$key] = '
                <li class="event-list">
                    <div class="event-timeline-dot">
                        <i class="bx bx-right-arrow-circle font-size-18"></i>
                    </div>
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <span class="badge bg-secondary" style="font-size:12px;"> ' . $value . '</span>
                        </div>
                    </div>
                </li>
        ';
    }

    return $data;
}

function cek_duplikasi_no_kontrak($data_no_kontrak)
{
    $unique_contracts = array_unique($data_no_kontrak);
    return count($unique_contracts) === count($data_no_kontrak);
}

function terbilang($angka)
{
    $angka = abs($angka);
    $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    $terbilang = "";

    if ($angka < 12) {
        $terbilang = " " . $baca[$angka];
    } elseif ($angka < 20) {
        $terbilang = terbilang($angka - 10) . " Belas ";
    } elseif ($angka < 100) {
        $terbilang = terbilang($angka / 10) . " Puluh " . terbilang($angka % 10);
    } elseif ($angka < 200) {
        $terbilang = " Seratus " . terbilang($angka - 100);
    } elseif ($angka < 1000) {
        $terbilang = terbilang($angka / 100) . " Ratus " . terbilang($angka % 100);
    } elseif ($angka < 2000) {
        $terbilang = " Seribu " . terbilang($angka - 1000);
    } elseif ($angka < 1000000) {
        $terbilang = terbilang($angka / 1000) . " Ribu " . terbilang($angka % 1000);
    } elseif ($angka < 1000000000) {
        $terbilang = terbilang($angka / 1000000) . " Juta " . terbilang($angka % 1000000);
    } elseif ($angka < 1000000000000) {
        $terbilang = terbilang($angka / 1000000000) . " Miliar " . terbilang($angka % 1000000000);
    } elseif ($angka < 1000000000000000) {
        $terbilang = terbilang($angka / 1000000000000) . " Triliun " . terbilang($angka % 1000000000000);
    }

    return trim($terbilang);
}


function format_rp($nominal)
{
    return number_format($nominal, 0, ',', '.');
}


function angkaKeKata($angka)
{
    $kata = [
        0 => '',
        1 => 'satu',
        2 => 'dua',
        3 => 'tiga',
        4 => 'empat',
        5 => 'lima',
        6 => 'enam',
        7 => 'tujuh',
        8 => 'delapan',
        9 => 'sembilan',
        10 => 'sepuluh',
        11 => 'sebelas',
        12 => 'dua belas',
        13 => 'tiga belas',
        14 => 'empat belas',
        15 => 'lima belas',
        16 => 'enam belas',
        17 => 'tujuh belas',
        18 => 'delapan belas',
        19 => 'sembilan belas',
        20 => 'dua puluh'
    ];

    if ($angka <= 20) {
        return $kata[$angka];
    } elseif ($angka < 100) {
        $puluhan = floor($angka / 10);
        $satuan = $angka % 10;
        return trim($kata[$puluhan] . ' puluh ' . $kata[$satuan]);
    }

    return $angka; // Untuk angka di luar jangkauan
}

function angkaKeTahun($tahun)
{
    // Mengambil setiap dua digit dari tahun
    $ribu = floor($tahun / 1000);
    $ratus = floor(($tahun % 1000) / 100);
    $puluhan = floor(($tahun % 100) / 10) * 10;
    $satuan = $tahun % 10;

    $result = '';
    if ($ribu) {
        $result .= 'dua ribu ';
    }
    if ($ratus) {
        $result .= ' ' . angkaKeKata($ratus) . ' ratus';
    }
    if ($puluhan || $satuan) {
        $result .= ' ' . angkaKeKata($puluhan + $satuan);
    }
    return trim($result);
}

function terjemahkanTanggalKeKata($tanggal)
{
    if ($tanggal == null) {
        return '';
    }
    // Array nama bulan dalam bahasa Indonesia
    $bulanIndonesia = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    // Memecah tanggal menjadi bagian-bagian (tahun, bulan, hari)
    $tanggalArray = explode('-', $tanggal);

    // Ambil bagian dari tanggal
    $tahun = (int) $tanggalArray[0];
    $bulan = (int) $tanggalArray[1]; // Convert bulan ke integer agar sesuai dengan indeks array
    $hari = (int) $tanggalArray[2];

    // Hasil terjemahan dengan angka ke kata
    $hariKata = angkaKeKata($hari);
    $tahunKata = angkaKeTahun($tahun);

    // Format akhir
    return ucfirst(trim($hariKata . ' ' . $bulanIndonesia[$bulan] . ' ' . $tahunKata));
}
function ubahFormatTglIdn($tanggal)
{
    if ($tanggal == null) {
        return '';
    }
    return date('d-m-Y', strtotime($tanggal));
}

function angkaKeKataJam($angka)
{
    $kata = [
        0 => 'nol',
        1 => 'satu',
        2 => 'dua',
        3 => 'tiga',
        4 => 'empat',
        5 => 'lima',
        6 => 'enam',
        7 => 'tujuh',
        8 => 'delapan',
        9 => 'sembilan',
        10 => 'sepuluh',
        11 => 'sebelas',
        12 => 'dua belas',
        13 => 'tiga belas',
        14 => 'empat belas',
        15 => 'lima belas',
        16 => 'enam belas',
        17 => 'tujuh belas',
        18 => 'delapan belas',
        19 => 'sembilan belas',
        20 => 'dua puluh',
        21 => 'dua puluh satu',
        22 => 'dua puluh dua',
        23 => 'dua puluh tiga',
        24 => 'dua puluh empat',
        25 => 'dua puluh lima',
        26 => 'dua puluh enam',
        27 => 'dua puluh tujuh',
        28 => 'dua puluh delapan',
        29 => 'dua puluh sembilan',
        30 => 'tiga puluh',
        31 => 'tiga puluh satu',
        32 => 'tiga puluh dua',
        33 => 'tiga puluh tiga',
        34 => 'tiga puluh empat',
        35 => 'tiga puluh lima',
        36 => 'tiga puluh enam',
        37 => 'tiga puluh tujuh',
        38 => 'tiga puluh delapan',
        39 => 'tiga puluh sembilan',
        40 => 'empat puluh',
        41 => 'empat puluh satu',
        42 => 'empat puluh dua',
        43 => 'empat puluh tiga',
        44 => 'empat puluh empat',
        45 => 'empat puluh lima',
        46 => 'empat puluh enam',
        47 => 'empat puluh tujuh',
        48 => 'empat puluh delapan',
        49 => 'empat puluh sembilan',
        50 => 'lima puluh',
        51 => 'lima puluh satu',
        52 => 'lima puluh dua',
        53 => 'lima puluh tiga',
        54 => 'lima puluh empat',
        55 => 'lima puluh lima',
        56 => 'lima puluh enam',
        57 => 'lima puluh tujuh',
        58 => 'lima puluh delapan',
        59 => 'lima puluh sembilan',
    ];

    return $kata[$angka];
}

function terjemahkanJam($jam)
{
    // Memisahkan jam dan menit
    list($jam, $menit) = explode(':', $jam);

    // Mengubah jam dan menit menjadi kata-kata
    $jamKata = angkaKeKataJam((int)$jam);
    $menitKata = angkaKeKataJam((int)$menit);

    return ucfirst($jamKata . ' ' . $menitKata . ' Waktu Indonesia Barat');
}
function terjemahkanHariKeIndonesia($hariInggris)
{
    // Daftar terjemahan nama hari dari bahasa Inggris ke bahasa Indonesia
    $namaHari = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];

    // Mengembalikan nama hari dalam bahasa Indonesia
    return $namaHari[$hariInggris] ?? 'Tidak diketahui'; // Untuk memastikan jika hari tidak dikenali
}

function custom_error_log($action, $table_db, $remarks)
{
    DB::enableQueryLog();
    DB::beginTransaction();
    try {
        $data = new ActivityLog();
        $data->users = session('user_id');
        $data->action = $action;
        $data->table_db = isset($table_db) ? $table_db : null;
        $data->account_code = session('akses');
        $data->remarks = $remarks;
        $data->save();
        DB::commit();
    } catch (\Throwable $th) {
        DB::rollBack();
        Log::error($th->getMessage());
    }

    DB::disableQueryLog();
    return true;
}

function ScanPDF($file)
{
    // dd($file);
    // $file = $request->data['file'];
    $pdfFile = $file;

    $parser = new Parser();
    try {
        $pdf = $parser->parseFile($pdfFile);
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal membaca file PDF. Mohon unggah file yang benar.');
    }

    $pages = $pdf->getPages();

    if (!isset($pages[0])) {
        return redirect()->back()->with('error', 'Tidak ada halaman yang ditemukan dalam file PDF.');
    }

    $text = $pages[0]->getText();
    $currentData = [];

    $nomorPattern = '/NOMOR\s+:\s+([A-Z0-9.]+)/';
    $tahunPattern = '/TAHUN\s+(\d{4})/';
    $tanggalPattern = '/TANGGAL\s+:\s+(\d{2}-\d{2}-\d{4})/';
    $jamPattern = '/JAM\s+:\s+(\d{2}:\d{2}:\d{2})/';

    if (preg_match($nomorPattern, $text, $nomorMatches)) {
        $currentData['nomor'] = $nomorMatches[1];
    }
    if (preg_match($tahunPattern, $text, $tahunMatches)) {
        $currentData['tahun'] = $tahunMatches[1];
    }
    if (preg_match($tanggalPattern, $text, $tanggalMatches)) {
        $currentData['tanggal'] = $tanggalMatches[1];
    }
    if (preg_match($jamPattern, $text, $jamMatches)) {
        $currentData['jam'] = $jamMatches[1];
    }
    // dd($currentData);
    if (!empty($currentData)) {
        $hasilDokumen = ['data' => $currentData];

        // return response()->json([
        //     'success' => true,
        //     'data' => $hasilDokumen,
        // ]);

        return $hasilDokumen;
    } else {
        return redirect()->back()->with('error', 'Dokumen tidak cocok. Mohon masukan data file yang benar.');
    }
}



function tambahWaktuJeda($datetime, $waktu_jeda)
{
    $date_now = date('Y-m-d');
    $datetime = $date_now . 'T' . $datetime;
    // dd($datetime);
    // Buat objek DateTime dari string datetime
    $dt = new DateTime($datetime);

    // Tambahkan waktu jeda dalam menit
    $dt->modify("+" . $waktu_jeda . " minutes");

    // Kembalikan waktu yang telah diubah dalam format yang diinginkan
    return $dt->format('Y-m-d\TH:i');
}


function getTanggal($tanggal_waktu)
{
    $tanggal = explode("T", $tanggal_waktu);
    return $tanggal[0]; // Mengambil bagian tanggal sebelum "T"
}


function formatTanggalWaktu($tanggal_waktu)
{
    return str_replace("T", " ", $tanggal_waktu);
}


function downloadZipWarkah($id_kontrak)
{
    $result = [];
    $result['is_valid'] = false;
    $data_warkah = SertificateWarkah::where('request_sertificate_contract', $id_kontrak)->get();

    if ($data_warkah->isNotEmpty()) {
        $fileName = 'warkah_' . $id_kontrak . '.zip';
        $filePath = public_path('berkas/warkah_zip/' . $fileName);

        $zip = new ZipArchive();
        $zip->open($filePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) == TRUE;
        foreach ($data_warkah as $value) {
            if (!empty($value->perjanjian_pembiayaan_path)) {
                $zip->addFile(public_path($value->perjanjian_pembiayaan_path . $value->perjanjian_pembiayaan_file), $value->perjanjian_pembiayaan_file);
            }
            if (!empty($value->skmjf_path)) {
                $zip->addFile(public_path($value->skmjf_path . $value->skmjf_file), $value->skmjf_file);
            }
            if (!empty($value->data_kendaraan_path)) {
                $zip->addFile(public_path($value->data_kendaraan_path . $value->data_kendaraan_file), $value->data_kendaraan_file);
            }
            if (!empty($value->kk_path)) {
                $zip->addFile(public_path($value->kk_path . $value->kk_file), $value->kk_file);
            }
            if (!empty($value->ktp_bpkb_path)) {
                $zip->addFile(public_path($value->ktp_bpkb_path . $value->ktp_bpkb_file), $value->ktp_bpkb_file);
            }
            if (!empty($value->ktp_debitur_path)) {
                $zip->addFile(public_path($value->ktp_debitur_path . $value->ktp_debitur_file), $value->ktp_debitur_file);
            }
            if (!empty($value->form_perjanjian_nama_bpkb_path)) {
                $zip->addFile(public_path($value->form_perjanjian_nama_bpkb_path . $value->form_perjanjian_nama_bpkb_file), $value->form_perjanjian_nama_bpkb_file);
            }
            if (!empty($value->ktp_pasangan_nama_bpkp_path)) {
                $zip->addFile(public_path($value->ktp_pasangan_nama_bpkp_path . $value->ktp_pasangan_nama_bpkp_file), $value->ktp_pasangan_nama_bpkp_file);
            }
            if (!empty($value->ktp_pasangan_debitur_path)) {
                $zip->addFile(public_path($value->ktp_pasangan_debitur_path . $value->ktp_pasangan_debitur_file), $value->ktp_pasangan_debitur_file);
            }
        }
        $zip->close();

        $result['is_valid'] = true;
        $result['file_url'] = asset('berkas/warkah_zip/' . $fileName);
    } else {
        $result['message'] = 'No data found for the specified contract.';
    }

    return $result;
}
