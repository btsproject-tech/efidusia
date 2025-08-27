<?php

namespace App\Http\Controllers\web\report;

use Illuminate\Http\Request;
// use App\Models\Master\Customer;
// use App\Models\Master\Dictionary;
use Illuminate\Support\Facades\DB;
use App\Models\Master\CompanyModel;
use App\Http\Controllers\Controller;
// use App\Models\Transaksi\InvoiceItem;
// use App\Models\Transaksi\QuotationItem;
use App\Models\Transaksi\InvoicingItemModel;
// use App\Models\Transaksi\ShippingExecutionGood;
// use App\Models\Transaksi\ShippingExecutionContainer;
use App\Http\Controllers\api\Transaksi\InvoiceController as TransaksiInvoiceController;

class ReportDailyController extends Controller
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
            'js-1' => asset('assets/js/controllers/report/dailyreport.js'),
        );
    }

    public function getTitleParent()
    {
        return "Report";
    }

    public function getTableName()
    {
        return "";
    }

    public function getTitle()
    {
        return "Daily Report";
    }

    public function getTitleNota()
    {
        return "Invoice Notaris";
    }

    public function getTitleFinance()
    {
        return "Invoice Finance";
    }

    public function index()
    {
        $data['data'] = [];
        $data['title'] = $this->getTitle();
        $data['akses'] = $this->akses_menu;
        // echo '<pre>';
        // print_r($data);die;
        // dd($data['akses']);
        $view = view('web.report_daily.index', $data);
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
        $data['titleNotaris'] = 'Form ' . $this->getTitleNota();
        $data['titleFinance'] = 'Form ' . $this->getTitleFinance();
        $data['data_notaris'] = CompanyModel::where('status', '!=', 'draft')->whereNull('deleted')->where('type', 'NOTARIS')->get()->toArray();
        $data['data_finance'] = CompanyModel::where('status', '!=', 'draft')->whereNull('deleted')->where('type', 'FINANCE')->get()->toArray();
        $view = view('web.invoice.formadd', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = 'Form ' . $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function getListDataRate($params)
    {
        $datadb = InvoicingItemModel::where('invoicing_item.invoice', $params->id)
            ->select([
                'invoicing_item.*',
            ])
            ->get()->toArray();
        return $datadb;
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
        $api = new TransaksiInvoiceController();
        $data = $request->all();
        $data['data'] = $api->getDetailData($data['id'])->original;
        $data['data_rate'] = $this->getListDataRate($data['data']);
        // dd($data);
        $data['data_notaris'] = CompanyModel::where('status', '!=', 'draft')->whereNull('deleted')->where('type', 'NOTARIS')->get()->toArray();
        $data['data_finance'] = CompanyModel::where('status', '!=', 'draft')->whereNull('deleted')->where('type', 'FINANCE')->get()->toArray();
        $data['titleNotaris'] = 'Form ' . $this->getTitleNota();
        $data['titleFinance'] = 'Form ' . $this->getTitleFinance();
        $data['title'] = 'Form ' . $this->getTitle();
        $view = view('web.invoice.formadd', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = 'Form ' . $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }


    public function detail(Request $request)
    {
        $api = new TransaksiInvoiceController();
        $data = $request->all();
        $data['company'] = CompanyModel::where('id', session('id_company'))->first();
        $data['data'] = $api->getDetailData($data['id'])->original;
        $data['total_data'] = $this->getCountData($data['id']);
        // $data['data_currency'] = Dictionary::whereNull('deleted')->where('context', 'CUR')->get()->toArray();
        // $data['data_item'] = $this->getListDataItem($data);
        // $data['data_container'] = $this->getListContainer($data['data']);
        // $data['data_containersize'] = current($this->getListContainerSize($data['data']));
        $data['data_item'] = $this->getListDataRate($data['data']);
        $data['title'] = 'Form ' . $this->getTitle();
        $view = view('web.invoice.form.detailinvoice', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = 'Form ' . $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
