<?php

namespace App\Http\Controllers\web\master;

use App\Http\Controllers\api\master\PaymentTermController as MasterPaymentTermController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentTermController extends Controller
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
            'js-1' => asset('assets/js/controllers/master/payment_term.js'),
        );
    }

    public function getTitleParent(){
        return "Master";
    }

    public function getTableName(){
        return "";
    }

    public function getTitle(){
        return "Payment Term";
    }

    public function index(){
        $data['data'] = [];
        $data['title'] = $this->getTitle();
        $data['akses'] = $this->akses_menu;
        $view = view('web.payment_term.index', $data);
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
       $view = view('web.payment_term.formadd', $data);
       $put['title_content'] = $this->getTitle();
       $put['title_top'] = 'Form '.$this->getTitle();
       $put['title_parent'] = $this->getTitleParent();
       $put['view_file'] = $view;
       $put['header_data'] = $this->getHeaderCss();
       return view('web.template.main', $put);
   }

   public function ubah(Request $request){
       $api = new MasterPaymentTermController();
       $data = $request->all();
       $data['data'] = $api->getDetailData($data['id'])->original;

       $data['title'] = 'Form '.$this->getTitle();
       $view = view('web.payment_term.formadd', $data);
       $put['title_content'] = $this->getTitle();
       $put['title_top'] = 'Form '.$this->getTitle();
       $put['title_parent'] = $this->getTitleParent();
       $put['view_file'] = $view;
       $put['header_data'] = $this->getHeaderCss();
       return view('web.template.main', $put);
   }
}
