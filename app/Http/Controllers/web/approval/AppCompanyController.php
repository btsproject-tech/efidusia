<?php

namespace App\Http\Controllers\web\approval;

use App\Http\Controllers\api\approval\AppCompanyController as MasterCompanyController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppCompanyController extends Controller
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
            'js-1' => asset('assets/js/controllers/approval/company.js'),
        );
    }

    public function getTitleParent()
    {
        return "Approval";
    }

    public function getTableName()
    {
        return "";
    }

    public function getTitle()
    {
        return "Perusahaan";
    }

    public function index()
    {
        $user_id = session('user_id');
        $name = session('nama_lengkap');
        $encrypted_user_id = Crypt::encrypt($user_id);
        $data['data'] = [];
        $data['title'] = $this->getTitle();
        $data['akses'] = $this->akses_menu;
        $data['user_id'] = $encrypted_user_id;
        $data['name'] = $name;
        // echo '<pre>';
        // print_r($data);die;
        $view = view('web.approval_company.index', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function downloadFile($id)
    {
        $file = DB::table('company_file')->where('id', $id)->first();
        if (!$file) {
            abort(404, 'File not found.');
        }

        try {
            $decryptedFilePath = Crypt::decrypt($file->file_path);
            Log::info('Decrypted file path: ' . $decryptedFilePath);
        } catch (\Exception $e) {
            abort(404, 'Invalid file path.');
        }

        $filePath = public_path($decryptedFilePath);

        if (!file_exists($filePath)) {
            Log::error('File not found on the server: ' . $filePath);
            abort(404, 'File not found on the server.');
        }


        return response()->download($filePath, $file->file);
    }

    public function getJenis()
    {
        return [
            'NOTARIS',
            'FINANCE',
        ];
    }

    public function add()
    {
        $data['data'] = [];
        $data['title'] = 'Form ' . $this->getTitle();
        $data['data_jenis'] = $this->getJenis();
        $data['akses'] = session('akses');
        $view = view('web.approval_company.formadd', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = 'Form ' . $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function ubah(Request $request)
    {
        $api = new MasterCompanyController();
        $data = $request->all();
        $data['data'] = $api->getDetailData($data['id'])->original;

        $data['title'] = 'Form ' . $this->getTitle();
        $data['akses'] = session('akses');
        $data['data_jenis'] = $this->getJenis();
        $view = view('web.company.formadd', $data);
        $put['title_content'] = $this->getTitle();
        $put['title_top'] = 'Form ' . $this->getTitle();
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }
}
