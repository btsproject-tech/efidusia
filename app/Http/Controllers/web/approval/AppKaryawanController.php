<?php

namespace App\Http\Controllers\web\approval;

use App\Http\Controllers\api\approval\AppKaryawanController as MasterKaryawanController;
use App\Http\Controllers\Controller;
use App\Models\Master\CompanyModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;

class AppKaryawanController extends Controller
{
    //
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
            'js-1' => asset('assets/js/controllers/approval/karyawan.js'),
        );
    }

    public function getTitleParent()
    {
        return "Approval";
    }

    public function getTableName()
    {
        return "Pengajuan Pengguna";
    }

    public function getTitle()
    {
        return "Pengajuan Pengguna";
    }

    public function index()
    {
        $user_id = session('user_id');
        $name = session('nama_lengkap');
        $encrypted_user_id = Crypt::encrypt($user_id);
        $data['data'] = [];
        $data['title'] = $this->getTableName();
        $data['akses'] = $this->akses_menu;
        $data['user_id'] = $encrypted_user_id;
        $data['name'] = $name;
        // echo '<pre>';
        // print_r(session()->all());die;
        $view = view('web.approval_karyawan.index', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function downloadTemplate()
    {
        $filePath = public_path('assets/doc/template/register_template.xlsx');

        if (File::exists($filePath)) {
            return response()->download($filePath);
        } else {
            return response()->json(['message' => 'File not found'], 404);
        }
    }

    public function add()
    {
        $data['data'] = [];
        $data['title'] = 'Form ' . $this->getTitle();
        $data['data_company'] = CompanyModel::whereNull('deleted')->where('status', 'APPROVED')->get()->toArray();
        $data['akses'] = session('akses');
        $data['company'] = session('id_company');
        $view = view('web.karyawan.formadd', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = 'Form ' . $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function ubah(Request $request)
    {
        $api = new MasterKaryawanController();
        $data = $request->all();
        $data['data'] = $api->getDetailData($data['id'])->original;
        $data['akses'] = session('akses');
        $data['company'] = session('id_company');
        $data['data_company'] = CompanyModel::whereNull('deleted')->where('status', 'APPROVED')->get()->toArray();

        $data['title'] = 'Form ' . $this->getTitle();
        $view = view('web.karyawan.formadd', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = 'Form ' . $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
