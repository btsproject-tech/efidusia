<?php

namespace App\Http\Controllers\web\auth;

use App\Http\Controllers\Controller;
use App\Models\Master\Users;
use App\Models\Master\UsersPermission;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    //
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index(Request $request)
    {
        $data = $request->all();
        return view('web.login.index', $data);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'username' => $request->get('username'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }


    public function signIn(Request $request)
    {
        $data = $request->all();
        $credentials = $request->only('username', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return redirect()->action('web\auth\LoginController@index', ['error' => 'Pengguna Tidak Ditemukan']);
            }
        } catch (JWTException $e) {
            return redirect()->action('web\auth\LoginController@index', ['error' => 'Pengguna Tidak Valid, Tidak Dapat Login.']);
        }
        // $user = $this->getAuthenticatedUser();

        $userdata = DB::table('users as usr')
            ->select([
                'usr.*',
                'ha.group as akses',
                'kry.company',
                'ut.nama_company',
                'kry.nama_lengkap'
            ])
            ->join('karyawan as kry', 'kry.nik', 'usr.nik')
            ->join('company as ut', 'ut.id', 'kry.company')
            ->join('users_group as ha', 'ha.id', '=', 'usr.user_group')
            ->where(function ($query) use ($data) {
                return $query->where('usr.username', '=', $data['username'])
                    ->orWhere('usr.nik', '=', $data['username']);
            })
            // ->where('usr.password', '=', $data['password'])
            ->whereNull('usr.deleted')
            ->first();

        // echo '<pre>';
        // print_r($userdata);die;

        if (!empty($userdata)) {
            // if ($data['password'] == $userdata->password) {
                $dataMenu = UsersPermission::where('users_permissions.users_group', $userdata->user_group)
                    ->select([
                        'users_permissions.*',
                        'am.nama as menu'
                    ])
                    ->join('menu as am', 'am.id', '=', 'users_permissions.menu')
                    ->whereNull('users_permissions.deleted')
                    ->get()->toArray();
                // echo '<pre>';
                // print_r($dataMenu);die;

                $result_akses = [];
                foreach ($dataMenu as $key => $value) {
                    $value['id_menu'] = strtolower(str_replace(' ', '_', $value['menu']));
                    $result_akses[$value['id_menu']] = $value;
                }

                $dataRoles = $this->checkDataRoles($userdata->nik);

                Session::put('user_id', $userdata->id);
                Session::put('nama_lengkap', $userdata->nama_lengkap);
                Session::put('username', $userdata->username);
                Session::put('akses', $userdata->akses);
                Session::put('nik', $userdata->nik);
                Session::put('id_company', $userdata->company);
                Session::put('area_kerja', $userdata->nama_company);
                Session::put('akses_menu', json_encode($result_akses));

                if (count($dataRoles) == 1) {
                    return redirect('dashboard');
                }
                return redirect('roles');
            // } else {
            //     return redirect()->action('web\auth\LoginController@index', ['error' => 'Password Salah']);
            // }
        } else {
            return redirect()->action('web\auth\LoginController@index', ['error' => 'Pengguna Tidak Ditemukan']);
        }
    }

    public function checkDataRoles($nik)
    {
        $data = Users::distinct()->select([
            'users.user_group'
        ])->where('users.nik', $nik)->whereNull('users.deleted')->get()->toArray();
        return $data;
    }

    public function signOut(Request $request)
    {
        session('user_id', '');
        session('username', '');
        session('akses', '');
        session('token', '');
        session('nik', '');
        session('akses_menu', '');
        session('company', '');
        session('id_company', '');
        Session::flush();
        return redirect('login')->with('success', 'Berhasil Keluar');
    }

    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json([
                'token_expired' => $e->getMessage()
            ]);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'token_invalid' => $e->getMessage()
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'token_absent' => $e->getMessage()
            ]);
        }

        return response()->json(compact('user'));
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token', 500]);
        }

        return response()->json(compact('token'));
    }
}
