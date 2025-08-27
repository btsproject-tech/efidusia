<?php

namespace App\Http\Controllers\api\Transaksi;

use App\Models\Master\Actor;
use Illuminate\Http\Request;
use App\Models\Master\Document;
use App\Models\Transaksi\Invoice;
use App\RequestCertificateNotaris;
use Illuminate\Support\Facades\DB;
use App\RequestCertificateContract;
use App\Http\Controllers\Controller;
use App\Models\Transaksi\InvoiceItem;
use App\Models\Transaksi\InvoicingModel;
use App\Models\Master\DocumentTransaction;
use App\Models\Transaksi\InvoicingItemModel;

class InvoiceController extends Controller
{
    public function getTableName()
    {
        return "invoicing";
    }

    public function getData(Request $request)
    {
        $data = $request->all();
        DB::enableQueryLog();
        $data['data'] = [];
        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $datadb = DB::table($this->getTableName() . ' as i')
            ->select([
                'i.*',
                'u.username',
                'k.nama_lengkap',
                'c.nama_company',
            ])
            ->join('users as u', 'u.id', 'i.creator')
            ->join('karyawan as k', 'k.nik', 'u.nik')
            ->leftJoin('company as c', 'c.id', 'i.company')
            ->whereNull('i.deleted')
            ->orderBy('i.id', 'desc');

        $userGroup = session('akses');
        $id_user = session('user_id');

        $user_id = DB::table('users as u')
            ->select('u.nik', 'k.company')
            ->join('karyawan as k', 'k.nik', 'u.nik')
            ->where('u.id', $id_user)
            ->get()
            ->toArray();
        // dd($user_id[0]);

        if (strcasecmp($userGroup, 'FINANCE') == 0) {
            $datadb->where(function ($q) use ($user_id) {
                return $q->where('i.company', $user_id[0]->company);
            });
        }

        if (strcasecmp($userGroup, 'NOTARIS') == 0) {
            $datadb->where(function ($q) use ($user_id) {
                return $q->where('i.company', $user_id[0]->company);
            });
        }

        if (strcasecmp($userGroup, 'VENDOR') == 0) {
            $datadb->where(function ($q) use ($id_user) {
                return $q->where('i.creator', $id_user);
            });
        }

        if (isset($data['tgl_awal'])) {
            if ($data['tgl_awal'] != '') {
                $datadb->where(function ($q) use ($data) {
                    return $q->where('i.invoice_date', '>=', $data['tgl_awal'])
                        ->where('i.invoice_date', '<=', $data['tgl_akhir']);
                });
            }
        }

        if (isset($_POST)) {
            $data['recordsTotal'] = $datadb->get()->count();
            if (isset($_POST['search']['value'])) {
                $keyword = $_POST['search']['value'];
                $datadb->where(function ($query) use ($keyword) {
                    $query->where('i.no_invoice', 'LIKE', '%' . $keyword . '%');
                    $query->orWhere('u.username', 'LIKE', '%' . $keyword . '%');
                    // $query->orWhere('q.no_quotation', 'LIKE', '%' . $keyword . '%');
                });
            }
            if (isset($_POST['order'][0]['column'])) {
                $datadb->orderBy('i.id', $_POST['order'][0]['dir']);
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
        DB::beginTransaction();
        try {
            if ($data['id'] == '') {
                $invc = new InvoicingModel();
                $invc->no_invoice = generateNoInvoicing();
                $invc->creator = session('user_id');
                $invc->company = $data['customer'];
                $invc->no_batch = $data['batch'];
                $invc->invoice_date = $data['invoice_date'];
                $invc->currency = 'IDR';
                $invc->roe = $data['roe'];
                $invc->amount = $data['amount'];
                $invc->total = $data['total'];
                $invc->grand_total = $data['total'];
                $invc->tax = $data['tax'];
                $invc->tipe_invoice = $data['tipe_invoice'];
                $invc->status = 'DRAFT';
                $invc->save();
                $invcId = $invc->id;

                foreach ($data['data_item'] as $key => $value) {
                    $invItem = new InvoicingItemModel();
                    $invItem->invoice = $invcId;
                    $invItem->subject = $value['subject'];
                    $invItem->currency = $value['currency'];
                    $invItem->rate = $value['rate'];
                    $invItem->unit = $value['unit'];
                    $invItem->qty = $value['qty'];
                    $invItem->save();
                }

                $actor = new Actor();
                $actor->users = session('user_id');
                $actor->content = $invc;
                $actor->action = 'Invoice dengan No. ' . $invc->no_invoice . ' telah dibuat';
                $actor->save();
            } else {
                $invcId = base64_decode($data['id']);
                $invc = InvoicingModel::find($invcId);
                $invc->invoice_date = $data['invoice_date'];
                $invc->company = $data['customer'];
                $invc->no_batch = $data['batch'];
                $invc->roe = $data['roe'];
                $invc->amount = $data['amount'];
                $invc->total = $data['total'];
                $invc->grand_total = $data['total'];
                $invc->tax = $data['tax'];
                $invc->tipe_invoice = $data['tipe_invoice'];
                $invc->save();

                InvoicingItemModel::where('invoice', $invcId)->delete();
                foreach ($data['data_item'] as $key => $value) {
                    $invItem = new InvoicingItemModel();
                    $invItem->invoice = $invcId;
                    $invItem->subject = $value['subject'];
                    $invItem->currency = $value['currency'];
                    $invItem->rate = $value['rate'];
                    $invItem->unit = $value['unit'];
                    $invItem->qty = $value['qty'];
                    $invItem->save();
                }

                $actor = new Actor();
                $actor->users = session('user_id');
                $actor->content = $invc;
                $actor->action = 'Invoice dengan No. ' . $invc->no_invoice . ' telah diubah';
                $actor->save();
            }

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            // throw $th;
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }
        return response()->json($result);
    }

    public function confirmDelete(Request $request)
    {
        $data = $request->all();
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {

            $invc = InvoicingModel::find($data['id']);
            $invc->deleted = date('Y-m-d H:i:s');
            $invc->save();

            $actor = new Actor();
            $actor->users = session('user_id');
            $actor->content = $invc;
            $actor->action = 'Invoice dengan No. ' . $invc->no_invoice . ' telah dihapus';
            $actor->save();

            DB::commit();
            $result['is_valid'] = true;
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
            $actor->action = 'Invoice dengan No. ' . $data['no_invoice'] . ' telah dikonfirmasi';
            $actor->save();

            $quo = InvoicingModel::find($data['id']);
            $quo->status = 'CONFIRMED';
            $quo->save();

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }
        return response()->json($result);
    }

    public function detailBacth(Request $request)
    {
        $data = $request->all();
    }

    public function getDetailData($id)
    {
        // DB::enableQueryLog();
        $datadb = DB::table($this->getTableName() . ' as i')
            ->select([
                'i.*',
                'c.nama_company',
                'c.npwp',
                'c.alamat',
                'c.no_hp',
                'u.name as nama',
            ])
            ->leftJoin('company as c', 'c.id', 'i.company')
            ->leftJoin('users as u', 'u.id', 'i.creator')
            ->whereNull('i.deleted')
            ->where('i.id', $id);
        $data = $datadb->first();
        // $query = DB::getQueryLog();
        // dd($data);
        return response()->json($data);
    }

    public function showDataBatch(Request $request)
    {
        $data = $request->all();
        return view('web.invoice.modal.dataperbacth', $data);
    }

    public function delete(Request $request)
    {
        $data = $request->all();
        return view('web.invoice.modal.confirmdelete', $data);
    }

    public function getDataBatch(Request $request)
    {
        $data = $request->all();
        DB::enableQueryLog();
        $data['data'] = [];
        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $datadb = RequestCertificateNotaris::with(['UserNotaris', 'DataRequestCertificate.Creator.Karyawan.CompanyKaryawan', 'RequestContract', 'RequestNotarisBacth'])
            ->whereNull('deleted')
            // ->whereHas('RequestNotarisBacth', function ($query) {
            //     $query->whereNull('deleted');
            // })
            // ->where('users', session('nik'))
            // ->where('id', '34')
            ->orderBy('id', 'desc');

        if (isset($_POST)) {
            $data['recordsTotal'] = $datadb->get()->count();
            if (isset($_POST['search']['value'])) {
                $keyword = $_POST['search']['value'];
                // $datadb->where(function ($query) use ($keyword) {
                //     $query->where('no_request', 'LIKE', '%' . $keyword . '%')
                //         ->orWhere('date_request', 'LIKE', '%' . $keyword . '%')
                //         ->orWhere('status', 'LIKE', '%' . $keyword . '%')
                //         ->orWhere('remarks', 'LIKE', '%' . $keyword . '%');
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
        // foreach ($data['data'] as $key => $value) {
        //     // dd($value['request_contract']);
        //     // Pastikan 'request_contract' ada dalam $value
        //     if (isset($value['request_contract']) && is_array($value['request_contract'])) {
        //         foreach ($value['request_contract'] as $row) {
        //             // dd($row);
        //             // Cek jika 'hutang_barang' ada dalam setiap $row
        //             if (isset($row['hutang_barang'])) {
        //                 $hutangBarang[] = $row['hutang_barang'];
        //                 $nilaiBarang[] = cari_biaya_barang($row['hutang_barang']);
        //                 // Tampilkan nilai 'hutang_barang'
        //                 // echo $row['hutang_barang'] . "<br>";
        //                 // echo '<pre>';
        //                 // print_r($query);die;
        //             }
        //         }
        //     }
        // }
        // $biaayall = array_sum($nilaiBarang);
        // dd($biaayall);

        $data['draw'] = $_POST['draw'];
        $query = DB::getQueryLog();
        // echo '<pre>';
        // dd($data['data']);
        // print_r($query);die;
        return response()->json($data);
    }

    public function exportDataInvoicing(Request $request)
    {
        $data = $request->all();
        $result = [
            'is_valid' => false,
            'message' => '',
            'order_item' => null,
            'order_detail' => null,
            'header' => null,
            'date_export' => date('Y-m-d H:i:s')
        ];

        try {
            $data_id = base64_decode($data['data_id']);
            $noInvoice = $data['data_invoice'];
            $datadb = RequestCertificateContract::with(['RequestContract', 'RequestContractStatusDailyReport', 'RequestCertificate.Creator.Karyawan.CompanyKaryawan'])
                ->whereNull('deleted')
                ->whereNotNull('delegate_to')
                ->where('request_sertificate_notaris', base64_decode($data['data_batch']))
                ->orderBy('id', 'desc');
            $data = $datadb->get()->toArray();

            $biaya_jasa = DB::table($this->getTableName() . ' as i');
            $biaya_jasa->join('invoicing_item as ii', 'ii.invoice', 'i.id');
            $biaya_jasa->whereNull('i.deleted');
            $biaya_jasa->where('i.id', $data_id);
            $biaya_jasa->select([
                'ii.rate as biaya_jasa',
            ]);
            $biaya_jasa = $biaya_jasa->first();

            $result['header'] = [
                'No' => 'No',
                'KodeCabangInduk' => 'KodeCabangInduk',
                'KodeCabangRep' => 'KodeCabangRep',
                'NamaCabang' => 'NamaCabang',
                'PemberiFidusia' => 'PemberiFidusia',
                'No_Perjanjian_Kontrak' => 'No_Perjanjian_Kontrak',
                'NoAkta' => 'NoAkta',
                'TanggalAkta' => 'TanggalAkta',
                'No_Sertifikat' => 'No_Sertifikat',
                'TanggalSertifikat' => 'TanggalSertifikat',
                'STATUS' => 'STATUS',
                'PNBP' => 'PNBP',
                'JASA' => 'JASA',
                'TOTAL' => 'TOTAL',

            ];

            $detail_get = [];
            $no = 1;
            foreach ($data as $key => $row) {
                $biayaJasaAkta = intval($biaya_jasa->biaya_jasa);
                $ktp = empty($row['ktp']) ? '-' : "\t" . $row['ktp'];
                $no_telp = empty($row['no_telp']) ? '-' : "\t" . $row['no_telp'];
                $kodecabangInduk = empty($row['cab']) ? '-' : $row['cab'];
                $kodecabangRep = empty($row['rep']) ? '-' : $row['rep'];
                $namacabang = empty($row['cab']) ? '-' : $row['cab'];
                $pemberiFidusia = empty($row['pemberi_fidusia']) ? '-' : $row['pemberi_fidusia'];
                $NoKontrak = empty($row['contract_number']) ? '-' : $row['contract_number'];
                $noAkta = empty($row['no_akta']) ? '-' : $row['no_akta'];
                $tgl_akta = empty($row['tanggal_akta']) ? '-' : $row['tanggal_akta'];
                $noSK = empty($row['no_sk']) ? '-' : $row['no_sk'];
                $tgl_serti = empty($row['tanggal_sertifikat']) ? '-' : $row['tanggal_sertifikat'];
                $biayaPnbp = empty($row['biaya_pnbp']) ? '-' : $row['biaya_pnbp'];
                $biayaJasa = empty($row['biaya_jasa']) ? $biayaJasaAkta : $row['biaya_jasa'];

                $detail_get[] = [
                    'No' => $no++,
                    'KodeCabangInduk' => $kodecabangInduk,
                    'KodeCabangRep' => $kodecabangRep,
                    'NamaCabang' => $namacabang,
                    'PemberiFidusia' => $pemberiFidusia,
                    'No_Perjanjian_Kontrak' => $NoKontrak,
                    'NoAkta' => $noAkta,
                    'TanggalAkta' => $tgl_akta,
                    'No_Sertifikat' => $noSK,
                    'TanggalSertifikat' => $tgl_serti,
                    'STATUS' => '-',
                    'PNBP' => $biayaPnbp,
                    'JASA' => $biayaJasa,
                    'TOTAL' => $biayaJasa + $biayaPnbp,
                ];
            }
            $result['order_detail'] = $detail_get;

            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            $result['message'] = $th->getMessage();
            $result['code'] = $th->getCode();
        }

        $actor = new Actor();
        $actor->users = session('user_id');
        $actor->content = 'Detail data invoice dengan No. ' . $noInvoice . ' telah diunduh oleh user ' . session('nama_lengkap');
        $actor->action = 'Detail data invoice dengan No. ' . $noInvoice . ' telah diunduh';
        $actor->save();

        $result['date_export'] = date('Y-m-d H:i:s');
        $result['date_export_ymd'] = date('ymd');
        return response()->json($result, 200);
    }
}
