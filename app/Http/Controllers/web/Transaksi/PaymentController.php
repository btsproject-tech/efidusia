<?php

namespace App\Http\Controllers\web\Transaksi;

use Illuminate\Http\Request;
use App\Models\Master\Customer;
use App\Models\Master\Dictionary;
use Illuminate\Support\Facades\DB;
use App\Models\Master\CompanyModel;
use App\Http\Controllers\Controller;
use App\Models\Transaksi\InvoiceItem;
use App\Models\Transaksi\QuotationItem;
use App\Models\Transaksi\InvoicingItemModel;
use App\Models\Transaksi\ShippingExecutionGood;
use App\Models\Transaksi\ShippingExecutionContainer;
use App\Http\Controllers\api\Transaksi\PaymentController as TransaksiInvoicePaymentController;

class PaymentController extends Controller
{
    public $akses_menu = [];
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->middleware(function ($request, $next) {
            $this->akses_menu = json_decode(session('akses_menu'));
            return $next($request);
        });
    }

    public function getHeaderCss()
    {
        return array(
            'js-1' => asset('assets/js/controllers/transaksi/invoice_payment.js'),
        );
    }

    public function getTitleParent()
    {
        return "Transaksi";
    }

    public function getTableName()
    {
        return "";
    }

    public function getTitle()
    {
        return "Invoice Payment";
    }

    public function index()
    {
        $data['data'] = [];
        $data['title'] = $this->getTitle();
        $data['akses'] = $this->akses_menu;
        // dd($data['akses']);
        // echo '<pre>';
        // print_r($data);die;
        $view = view('web.invoice_payment.index', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function add()
    {
        $data['data'] = [];
        $data['title'] = 'Form ' . $this->getTitle();
        $data['data_invoice'] = DB::table('invoicing as i')->select('i.*', 'c.nama_company')->join('company as c', 'c.id', 'i.company')->where('i.status', 'CONFIRMED')->whereNull('i.deleted')->get()->toArray();
        $view = view('web.invoice_payment.formadd', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = 'Form ' . $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function getListDataRate($params)
    {
        $datadb = InvoicingItemModel::where('invoicing_item.invoice', $params)
            ->select([
                'invoicing_item.*',
            ])
            ->orderBy('id', 'asc')
            ->limit(InvoicingItemModel::where('invoicing_item.invoice', $params)->count() - 1)
            ->get()->toArray();
        return $datadb;
    }

    public function biayaPnbp($params)
    {
        $datadb = InvoicingItemModel::where('invoicing_item.invoice', $params)
            ->select([
                'invoicing_item.*',
            ])
            ->orderBy('id', 'desc')
            ->first();
        $original = $datadb->getOriginal();
        return $original;
    }

    public function getCountData($params)
    {
        $query = DB::table('invoicing_item as ii')
            ->where('ii.invoice', $params)
            ->sum('ii.qty');
        return $query;
    }

    public function ubah(Request $request)
    {
        $api = new TransaksiInvoicePaymentController();
        $data = $request->all();
        $data['data'] = $api->getDetailData(base64_decode($data['id']))->original;
        $data['data_item'] = DB::table('invoicing_item as ii')->where('ii.invoice', $data['data']->invoice_id)->get()->toArray();
        $data['data_invoice'] = DB::table('invoicing as i')->select('i.*', 'c.nama_company')->join('company as c', 'c.id', 'i.company')->where('i.status', 'CONFIRMED')->orWhere('i.status', 'PAID')->whereNull('i.deleted')->get()->toArray();
        $data['total_data'] = $this->getCountData($data['data']->invoice_id);
        $data['title'] = 'Form ' . $this->getTitle();
        $data['file_tipe'] = pathinfo($data['data']->file_payment, PATHINFO_EXTENSION);
        $view = view('web.invoice_payment.formadd', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = 'Form ' . $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function detail(Request $request)
    {
        $api = new TransaksiInvoicePaymentController();
        $data = $request->all();
        $data['data'] = $api->getDetailData(base64_decode($data['id']))->original;
        $data['rate_akhir'] = $this->biayaPnbp($data['data']->invoicing);
        $data['data_item'] = $this->getListDataRate($data['data']->invoicing);
        $data['company'] = CompanyModel::where('id', $data['data']->company)->first();
        $data['total_data'] = $this->getCountData($data['data']->invoice_id);
        $data['title'] = 'Form ' . $this->getTitle();
        $angka = $data['data']->grand_total;
        $data['terbilang'] = terbilang($angka) . ' Rupiah';
        $view = view('web.invoice_payment.form.detailinvoice', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = 'Form ' . $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function getListDataItemCost($params)
    {
        // $datadb = CostItem::where('cost_item.cost', $params['id'])
        // ->get()->toArray();
        // return $datadb;
    }

    public function getListDataItemRevenue($params)
    {
        // $datadb = CostRevenue::where('cost_revenue.cost', $params['id'])
        // ->get()->toArray();
        // return $datadb;
    }
}
