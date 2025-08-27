<?php

namespace App\Http\Controllers\web\master;

use App\Http\Controllers\api\master\SaksiController as MasterSaksiController;
use App\Http\Controllers\Controller;
use App\Models\Master\Branch;
use App\Models\Master\CompanyModel;
use Illuminate\Http\Request;

class SaksiController extends Controller
{
    public $akses_menu = [];
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
        $this->middleware(function($request, $next){
            $this->akses_menu = json_decode(session('akses_menu'));
            return $next($request);
        });
    }

    public function getHeaderCss()
    {
        return array(
            'js-1' => asset('assets/js/controllers/master/saksi.js'),
        );
    }

    public function getTitleParent(){
        return "Data";
    }

    public function getTableName(){
        return "";
    }

    public function getTitle(){
        return "Saksi";
    }

    public function index(){
        $data['data'] = [];
        $data['title'] = $this->getTitle();
        $data['akses'] = $this->akses_menu;
        // echo '<pre>';
        // print_r(session()->all());die;
        $view = view('web.saksi.index', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function add(){
        $data['data'] = [];
        $data['title'] = 'Form '.$this->getTitle();
        $data['data_company'] = CompanyModel::whereNull('deleted')->where('status', 'APPROVED')->get()->toArray();
        $data['data_status'] = ['SAKSI1', 'SAKSI2'];
        $data['akses'] = session('akses');
        $data['company'] = session('id_company');
        $view = view('web.saksi.formadd', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = 'Form '.$this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function ubah(Request $request){
        $api = new MasterSaksiController();
        $data = $request->all();
        $data['data'] = $api->getDetailData($data['id'])->original;
        $data['akses'] = session('akses');
        $data['company'] = session('id_company');
        $data['data_company'] = CompanyModel::whereNull('deleted')->where('type', 'NOTARIS')->get()->toArray();
        $data['data_status'] = ['SAKSI1', 'SAKSI2'];

        $data['title'] = 'Form '.$this->getTitle();
        $view = view('web.saksi.formadd', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = 'Form '.$this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
