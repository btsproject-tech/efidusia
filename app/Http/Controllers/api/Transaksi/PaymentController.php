<?php

namespace App\Http\Controllers\api\Transaksi;

use App\Models\Master\Actor;
use Illuminate\Http\Request;
use App\Models\Master\Document;
use App\Models\Transaksi\Invoice;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\Transaksi\InvoiceItem;
use App\Models\Transaksi\InvoicingModel;
use App\Models\Master\DocumentTransaction;
use App\Models\Transaksi\ShippingContainer;
use App\Models\Transaksi\InvoicePaymentModel;
use App\Models\Transaksi\ShippingExecutionGood;

class PaymentController extends Controller
{
    public function getTableName()
    {
        return "payment_invoice";
    }

    public function getData(Request $request)
    {
        $data = $request->all();
        DB::enableQueryLog();
        $data['data'] = [];
        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $datadb = DB::table($this->getTableName() . ' as pi')
            ->select([
                'pi.*',
                'i.invoice_date',
                'i.no_invoice as invoice_no',
            ])
            ->join('invoicing as i', 'i.id', 'pi.invoicing')
            ->whereNull('pi.deleted')
            ->orderBy('pi.id', 'desc');

        if (isset($data['tgl_awal'])) {
            if ($data['tgl_awal'] != '') {
                $datadb->where(function ($q) use ($data) {
                    return $q->where('pi.payment_date', '>=', $data['tgl_awal'])
                        ->where('pi.payment_date', '<=', $data['tgl_akhir']);
                });
            }
        }

        if (isset($_POST)) {
            $data['recordsTotal'] = $datadb->get()->count();
            if (isset($_POST['search']['value'])) {
                $keyword = $_POST['search']['value'];
                $datadb->where(function ($query) use ($keyword) {
                    $query->where('pi.no_invoice', 'LIKE', '%' . $keyword . '%');
                    $query->orWhere('pi.no_payment', 'LIKE', '%' . $keyword . '%');
                    // $query->orWhere('q.no_quotation', 'LIKE', '%' . $keyword . '%');
                });
            }
            if (isset($_POST['order'][0]['column'])) {
                $datadb->orderBy('pi.id', $_POST['order'][0]['dir']);
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

    public function getDataInv(Request $request)
    {
        $data  = $request->all();
        // dd($data);
        DB::beginTransaction();
        $resp['data'] = DB::table('invoicing as i')->where('i.no_invoice', $data['invoice'])->first();
        $resp['data']->item = DB::table('invoicing_item as ii')->where('ii.invoice', $data['id'])->get()->toArray();
        $resp['data']->total_qty = DB::table('invoicing_item as ii')->where('ii.invoice', $resp['data']->id)->sum('ii.qty');
        // dd($resp['data']);
        DB::commit();
        return response()->json($resp);
    }

    public function submit(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            if ($data['id'] == '') {
                $invPay = new InvoicePaymentModel();
                $invPay->creator = session('user_id');
                $invPay->no_payment = generateNoPayment();
                $invPay->no_invoice = $data['no_invoice'];
                $invPay->invoicing = $data['invoicing'];
                $invPay->payment_date = $data['payment_date'];
                $invPay->total_payment = $data['total'];
                $invPay->remarks = $data['remarks'];
                $invPay->status = 'DRAFT';

                $imageName = $data['bukti_transfer']['file_name'];
                $dir = 'berkas/document/payment/';
                $dir .= date('Y') . '/' . date('m');
                $pathlamp = public_path() . '/' . $dir . '/';
                if (!File::isDirectory($pathlamp)) {
                    File::makeDirectory($pathlamp, 0777, true, true);
                }

                if ($data['bukti_transfer']['tipe'] != '') {
                    uploadFileFromBlobString($pathlamp, $imageName, $data['bukti_transfer']['file']);
                } else {
                    File::put($pathlamp . $imageName, base64_decode($data['bukti_transfer']['file']));
                }
                $dbpathlamp = '/' . $dir . '/';

                $invPay->file_payment = $imageName;
                $invPay->path_file = $dbpathlamp;
                $invPay->save();

                $invc = InvoicingModel::find($data['invoicing']);
                $invc->status = 'PAID';
                $invc->save();

                $actor = new Actor();
                $actor->users = session('user_id');
                $actor->content = $invPay;
                $actor->action = 'Invoice dengan No. ' . $invPay->no_invoice . ' telah dibayar, dengan no pembayaran berikut ' . $invPay->no_payment;
                $actor->save();
            } else {
                $invPay = InvoicePaymentModel::find(base64_decode($data['id']));
                $invPay->creator = session('user_id');
                $invPay->no_invoice = $data['no_invoice'];
                $invPay->invoicing = $data['invoicing'];
                $invPay->payment_date = $data['payment_date'];
                $invPay->total_payment = $data['total'];
                $invPay->remarks = $data['remarks'];
                $invPay->status = 'DRAFT';

                $imageName = $data['bukti_transfer']['file_name'];
                $dir = 'berkas/document/payment/';
                $dir .= date('Y') . '/' . date('m');
                $pathlamp = public_path() . '/' . $dir . '/';
                if (!File::isDirectory($pathlamp)) {
                    File::makeDirectory($pathlamp, 0777, true, true);
                }
                if ($data['bukti_transfer']['tipe'] != '') {
                    uploadFileFromBlobString($pathlamp, $imageName, $data['bukti_transfer']['file']);
                } else {
                    File::put($pathlamp . $imageName, base64_decode($data['bukti_transfer']['file']));
                }
                $dbpathlamp = '/' . $dir . '/';

                $invPay->file_payment = $imageName;
                $invPay->path_file = $dbpathlamp;
                $invPay->save();

                $actor = new Actor();
                $actor->users = session('user_id');
                $actor->content = $invPay;
                $actor->action = 'Invoice payment dengan No. ' . $invPay->no_payment . ' telah diubah';
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
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            $invPay = InvoicePaymentModel::find(base64_decode($data['id']));
            $invPay->deleted = date('Y-m-d H:i:s');
            $invPay->save();

            $invc = InvoicingModel::find(base64_decode($data['invoice_id']));
            $invc->status = 'CONFIRMED';
            $invc->save();

            $actor = new Actor();
            $actor->users = session('user_id');
            $actor->content = $invPay;
            $actor->action = 'Invoice payment dengan No. ' . $invPay->no_payment . ' telah dihapus';
            $actor->save();

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            //throw $th;
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
            $actor->action = 'Payment dengan No. ' . $data['no_payment'] . ' telah dikonfirmasi';
            $actor->save();
            $actId = $actor->id;

            $quo = InvoicePaymentModel::find($data['id']);
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
        $datadb = DB::table($this->getTableName() . ' as pi')
            ->select([
                'pi.*',
                'i.id as invoice_id',
                'i.no_invoice',
                'i.invoice_date',
                'i.company',
                'i.roe',
                'i.amount',
                'i.grand_total',
                'i.tax',
                'i.materai',
                'i.currency',
                'c.nama_company',
                'c.alamat',
                'c.npwp',
                'c.no_hp',
                'u.name as nama',
            ])
            ->join('invoicing as i', 'i.id', 'pi.invoicing')
            ->leftJoin('company as c', 'c.id', 'i.company')
            ->leftJoin('users as u', 'u.id', 'i.creator')
            ->whereNull('pi.deleted')
            ->where('pi.id', $id);
        $data = $datadb->first();
        $query = DB::getQueryLog();
        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $data = $request->all();
        return view('web.invoice_payment.modal.confirmdelete', $data);
    }

    public function showDataInvoice(Request $request)
    {
        $data = $request->all();
        return view('web.invoice_payment.modal.dataInvoicing', $data);
    }

    public function showBukti(Request $request)
    {
        $data = $request->all();
        return view('web.invoice_payment.modal.viewData', $data);
    }

    public function duplicate(Request $request)
    {
        $data = $request->all();
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {

            /*INSERT INVOICE */
            $dataInvoice = Invoice::where('id', $data['id'])->first();
            if (!empty($dataInvoice)) {
                $noDoc = generateNoDocument();
                $doc = new Document();
                $doc->no_document = $noDoc;
                $doc->tipe = 'DOCT_INV';
                $doc->save();
                $docId = $doc->id;

                $inv = $dataInvoice->replicate();
                $inv->document = $docId;
                $inv->no_invoice = generateNoInvoice();
                $inv->shipping_excecution = NULL;
                $inv->status = 'CREATED';
                $inv->save();
                $invId = $inv->id;

                $dataInvoiceItem = InvoiceItem::where('invoice', $dataInvoice->id)->get()->toArray();
                if (!empty($dataInvoiceItem)) {
                    foreach ($dataInvoiceItem as $key => $value) {
                        $invItem = InvoiceItem::find($value['id']);
                        $newInvItem = $invItem->replicate();
                        $newInvItem->invoice = $invId;
                        $newInvItem->save();
                    }
                }

                $actor = new Actor();
                $actor->users = session('user_id');
                $actor->content = $inv;
                $actor->action = 'CREATED INVOICE ' . $noDoc;
                $actor->save();
                $actId = $actor->id;

                $docTrans = new DocumentTransaction();
                $docTrans->no_document = $noDoc;
                $docTrans->actors = $actId;
                $docTrans->state = 'CREATED';
                $docTrans->save();
            }
            /*INSERT INVOICE */

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $result['message'] = $th->getMessage();
        }

        return response()->json($result);
    }
}
