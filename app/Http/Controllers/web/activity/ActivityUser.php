<?php

namespace App\Http\Controllers\web\activity;

use Illuminate\Http\Request;
use App\Models\Master\Customer;
use App\Models\Master\Dictionary;
use App\RequestCertificateNotaris;
use Illuminate\Support\Facades\DB;
use App\Models\Master\CompanyModel;
use App\Http\Controllers\Controller;
use App\Models\Transaksi\InvoiceItem;
use App\Models\Transaksi\QuotationItem;
use App\Models\Transaksi\InvoicingItemModel;
use App\Models\Transaksi\ShippingExecutionGood;
use App\Models\Transaksi\ShippingExecutionContainer;
use App\Http\Controllers\api\Transaksi\InvoiceController as TransaksiInvoiceController;

class ActivityUser extends Controller
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
            'js-1' => asset('assets/js/controllers/activity/activity_user.js'),
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
        return "Activity";
    }

    public function getTitleNota()
    {
        return "Activity Users";
    }

    public function index()
    {
        $data['data'] = [];
        $data['title'] = $this->getTitle();
        $data['akses'] = $this->akses_menu;
        $view = view('web.activity_user.index', $data);
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
            ->orderBy('id', 'asc')
            ->limit(InvoicingItemModel::where('invoicing_item.invoice', $params->id)->count() - 1)
            ->get()->toArray();
        return $datadb;
    }

    public function getListDataDetail($params)
    {
        $datadb = InvoicingItemModel::where('invoicing_item.invoice', $params->id)
            ->select([
                'invoicing_item.*',
            ])
            ->orderBy('id', 'asc')
            ->get()->toArray();
        return $datadb;
    }

    public function biayaPnbp($params)
    {
        $datadb = InvoicingItemModel::where('invoicing_item.invoice', $params->id)
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

    public function batch($params)
    {
        $datadb = RequestCertificateNotaris::with(['UserNotaris', 'DataRequestCertificate', 'RequestContract', 'RequestNotarisBacth'])
            ->whereNull('deleted')
            ->where('id', $params->no_batch)
            ->get()->toArray();
        // ->first();
        return $datadb;
    }

    public function ubah(Request $request)
    {
        $api = new TransaksiInvoiceController();
        $data = $request->all();
        $data['data'] = $api->getDetailData($data['id'])->original;
        $data['company'] = CompanyModel::where('id', $data['data']->company)->first();
        $data['batch'] = $this->batch($data['data']);
        $data['data_pnbp'] = $this->biayaPnbp($data['data']);
        $data['data_rate'] = $this->getListDataRate($data['data']);
        // $data['data_finance'] = CompanyModel::where('status', '!=', 'draft')->whereNull('deleted')->where('type', 'FINANCE')->get()->toArray();
        // $data['titleNotaris'] = 'Form ' . $this->getTitleNota();
        $data['titleFinance'] = 'Form ' . $this->getTitleFinance();
        $data['title'] = 'Form ' . $this->getTitle();
        // dd($data['company']['biaya_jasa']);
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
        $data['data'] = $api->getDetailData($data['id'])->original;
        $data['total_data'] = $this->getCountData($data['id']);
        $data['rate_akhir'] = $this->biayaPnbp($data['data']);
        $angka = $data['data']->grand_total;
        $data['terbilang'] = terbilang($angka) . ' Rupiah';
        $data['data_item'] = $this->getListDataRate($data['data']);
        $data['title'] = 'Form ' . $this->getTitle();
        $data['akses'] = session('akses');
        $view = view('web.invoice.form.detailinvoice', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = 'Form ' . $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
