<?php

namespace App\Http\Controllers\web\certificate;

use App\Http\Controllers\api\certificate\RequestCertificateNotarisController as ApiRequestCertificateNotarisController;
use App\Http\Controllers\Controller;
use App\Models\Master\CompanyModel;
use Illuminate\Http\Request;

class RequestCertificateNotarisController extends Controller
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
            'js-1' => asset('https://cdn.tiny.cloud/1/zhv5929em5ibgqkdjs7ie5xaub4pbmju4wtmgr3hholpj1xf/tinymce/6/tinymce.min.js'),
            'js-2' => asset('assets/js/controllers/certificate/request_certificate_notaris.js'),
        );
    }

    public function getTitleParent()
    {
        return "Sertifikat";
    }

    public function getTableName()
    {
        return "";
    }

    public function getTitle()
    {
        return "Verfikasi Sertificate Notaris";
    }

    public function index()
    {
        $data['data'] = [];
        $data['title'] = $this->getTitle();
        $data['akses'] = $this->akses_menu;
        // dd($data['akses']);
        // echo '<pre>';
        // print_r($data);die;
        $view = view('web.verifikasi_certificate_notaris.index', $data);
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
        $view = view('web.verifikasi_certificate_notaris.formadd', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = 'Form ' . $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function ubah(Request $request)
    {
        $api = new ApiRequestCertificateNotarisController();
        $data = $request->all();
        $data['data'] = $api->getDetailData($data['id'])->original;
        //    echo '<pre>';
        //    print_r($data);die;
        $data['title'] = 'Form ' . $this->getTitle();
        $view = view('web.verifikasi_certificate_notaris.formadd', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = 'Form ' . $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function detail(Request $request)
    {
        $api = new ApiRequestCertificateNotarisController();
        $data = $request->all();
        $data['data'] = $api->getDetailData($data['id'])->original;
        $view = view('web.verifikasi_certificate_notaris.form.detail_req_certificate', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = 'Detail Data ' . $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
