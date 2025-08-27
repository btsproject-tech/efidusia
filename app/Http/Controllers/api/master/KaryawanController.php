<?php

namespace App\Http\Controllers\api\master;

use App\Http\Controllers\Controller;
use App\Models\Master\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function getTableName(){
        return "karyawan";
    }

    public function getData(){
        DB::enableQueryLog();
        $data['data'] = [];
        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $company = session('id_company');
        $akses = session('akses');

        $datadb = DB::table($this->getTableName().' as m')
        ->select([
            'm.*',
            'u.nama_company'
        ])
        ->join('company as u', 'u.id', 'm.company')
        ->whereNull('m.deleted');

        if(strtolower($akses) != 'superadmin'){
            $datadb->where('u.id', $company);
        }

        if(isset($_POST)){
            $data['recordsTotal'] = $datadb->get()->count();
            if(isset($_POST['search']['value'])){
                $keyword = $_POST['search']['value'];
                $datadb->where(function($query) use ($keyword){
                    $query->where('u.nama_company', 'LIKE', '%'.$keyword.'%');
                    $query->orWhere('m.nama_lengkap', 'LIKE', '%'.$keyword.'%');
                    $query->orWhere('m.nik', 'LIKE', '%'.$keyword.'%');
                    $query->orWhere('m.jabatan', 'LIKE', '%'.$keyword.'%');
                });
            }
            if(isset($_POST['order'][0]['column'])){
                $datadb->orderBy('m.id', $_POST['order'][0]['dir']);
            }
            $data['recordsFiltered'] = $datadb->get()->count();

            if(isset($_POST['length'])){
                $datadb->limit($_POST['length']);
            }
            if(isset($_POST['start'])){
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

    public function submit(Request $request){
        $data = $request->all();
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            //code...
            $roles = $data['id'] == '' ? new Karyawan() : Karyawan::find($data['id']);
            $roles->company = $data['company'];
            $roles->branch = $data['branch'];
            $roles->nik = $data['nik'];
            $roles->nama_lengkap = $data['nama'];
            $roles->jabatan = $data['jabatan'];
            $roles->contact = $data['contact'];
            $roles->email = $data['email'];
            $roles->save();

            // $nik_upt = new KaryawanHasUpt();
            // $nik_upt->nik = $data['nik'];
            // $nik_upt->nama = $data['nama'];
            // $nik_upt->upt = $data['upt'];
            // $nik_upt->save();

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            //throw $th;
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }
        return response()->json($result);
    }

    public function confirmDelete(Request $request){
        $data = $request->all();
        $result['is_valid'] = false;
        DB::beginTransaction();
        try {
            //code...
            $menu = Karyawan::find($data['id']);
            $menu->deleted = date('Y-m-d H:i:s');
            $menu->save();

            DB::commit();
            $result['is_valid'] = true;
        } catch (\Throwable $th) {
            //throw $th;
            $result['message'] = $th->getMessage();
            DB::rollBack();
        }
        return response()->json($result);
    }

    public function getDetailData($id){
        DB::enableQueryLog();
        $datadb = DB::table($this->getTableName().' as m')
        ->select([
            'm.*',
        ])->where('m.id', $id);
        $data = $datadb->first();
        $query = DB::getQueryLog();
        return response()->json($data);
    }

    public function delete(Request $request){
        $data = $request->all();
        return view('web.karyawan.modal.confirmdelete', $data);
    }
}
