<?php

namespace App\Http\Controllers\web\register;

use App\Http\Controllers\Controller;
use App\Models\Master\CompanyModel;
use App\Models\Master\Actor;
use App\Models\Master\Users;
use App\Models\Master\Karyawan;
use App\Models\Master\CompanyBranch;
use App\Models\Transaksi\CompanyFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $groups = DB::table('users_group')
            ->whereIn('group', ['finance', 'notaris'])
            ->get(['id', 'group'])
            ->keyBy('group');
        $branch = DB::table('branch')
            ->get(['id', 'nama_cabang', 'kode_cabang']);
        $companyBranch = DB::table('company_branch')
            ->get(['id', 'company', 'branch']);
        $companies = DB::table('company')
            ->where('status', 'APPROVED')
            ->get(['id', 'nama_company', 'type', 'status', 'category'])
            ->map(function ($company) use ($groups) {
                $normalizedType = strtolower($company->type);
                $groupName = isset($groups[$normalizedType]) ? $groups[$normalizedType]->group : null;
                $groupId = isset($groups[$normalizedType]) ? $groups[$normalizedType]->id : null;

                return [
                    'id' => $company->id,
                    'nama_company' => $company->nama_company,
                    'type' => $company->type,
                    'groupId' => $groupId,
                    'group_name' => $groupName,
                    'status' => $company->status,
                    'category' => $company->category,
                ];
            });

        $data = [
            'branch' =>  $branch,
            'groups' => $groups,
            'companys' => $companies,
            'companyBranch' => $companyBranch,
        ];
        return view('web.register.index', $data);
    }

    public function registerCompany(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nama_company' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'provinsi_name_company' => 'required|string|max:255',
            'kota_name_company' => 'required|string|max:255',
            'kecamatan_name_company' => 'required|string|max:255',
            'keldesa_name_company' => 'required|string|max:255',
            'type' => 'required|in:NOTARIS,FINANCE',
            'no_hp' => 'required|digits_between:11,13',
            'email' => 'required|email|max:255|unique:company,email',
            'file' => 'nullable|mimes:doc,docx,pdf|max:5048',
            'cabang' => 'required|array',
            'cabang.*' => 'required|exists:branch,id',
            'npwp' => 'required|digits:16',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data gagal disimpan',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $company = new CompanyModel();
            $company->nama_company = $request->get('nama_company');
            $company->alamat = $request->get('alamat');
            $company->type = $request->get('type');
            $company->no_hp = $request->get('no_hp');
            $company->email = $request->get('email');
            $company->provinsi = $request->get('provinsi_name_company');
            $company->kota = $request->get('kota_name_company');
            $company->kecamatan = $request->get('kecamatan_name_company');
            $company->kel_desa = $request->get('keldesa_name_company');

            $npwp = $request->get('npwp');
            $formattedNpwp = preg_replace('/(\d{4})(?=\d)/', '$1 ', $npwp);
            $company->npwp = $formattedNpwp;

            $company->status = 'DRAFT';
            $company->category = 'PT';
            $company->save();

            $branches = $request->get('cabang');
            foreach ($branches as $branchId) {
                $companyBranch = new CompanyBranch();
                $companyBranch->company = $company->id;
                $companyBranch->branch = $branchId;
                $companyBranch->save();
            }

            if ($request->hasFile('files')) {
                $dokumenFiles = $request->file('files');
                foreach ($dokumenFiles as $dokumen) {
                    $nama_file = time() . '_' . $dokumen->getClientOriginalName();
                    $path_file = 'berkas/kontrak/' . $nama_file;
                    $dokumen->move(public_path('berkas/kontrak'), $nama_file);

                    $encrypted_file_path = Crypt::encrypt($path_file);

                    $companyFile = new CompanyFile();
                    $companyFile->file = $nama_file;
                    $companyFile->file_path = $encrypted_file_path;
                    $companyFile->remarks = $request->get('nama_company');
                    $companyFile->save();
                }
            }

            $action = new Actor();
            $action->users = $company->id;
            $action->content = 'Akun Perusahaan ' . $request->get('nama_company') . ' Telah melakukan registrasi';
            $action->action = 'Perusahaan ' . $request->get('nama_company') . ' Telah melakukan registrasi';
            $action->created_at = now();
            $action->save();

            $phoneNumber = '+62' . ltrim($request->get('no_hp'), '0');
            $message = "Perusahaan {$request->get('nama_company')} telah berhasil melakukan registrasi, mohon ditunggu untuk proses verifikasinya. Jika ada pertanyaan, silahkan hubungi customer service kami. Terima Kasih.";

            try {
                sendFonteNotification($phoneNumber, $message);
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim notifikasi: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Data berhasil disimpan',
                'data' => $company
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Data gagal disimpan',
                'errors' => ['error' => 'Gagal menyimpan data: ' . $e->getMessage()]
            ], 500);
        }
    }

    public function registerUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'provinsi_name_user' => 'required|string|max:255',
            'kota_name_user' => 'required|string|max:255',
            'kecamatan_name_user' => 'required|string|max:255',
            'keldesa_name_user' => 'required|string|max:255',
            'domisili' => 'required|string|max:255',
            'userAlamat' => 'required|string|max:500',
            'nik' => 'required|digits:16|unique:karyawan,nik',
            'company' => 'required|exists:company,id',
            'user-contact' => 'required|digits_between:11,13',
            'jabatan' => 'required|string|max:255',
            'user-email' => 'required|email|max:255|unique:karyawan,email',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'userTypeValue' => 'required|integer|exists:users_group,id',
            'tgl_lahir' => 'required|date',
            'tmp_lahir' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data gagal disimpan',
                'errors' => $validator->errors()
            ], 400);
        }

        $user = new Users();
        $user->username = $request->get('username');
        $user->name = $request->get('nama_lengkap');
        $user->nik = $request->get('nik');
        $user->password = Hash::make($request->get('password'));
        $user->user_group = $request->get('userTypeValue');
        $user->save();

        $karyawan = new Karyawan();
        $karyawan->nama_lengkap = $request->get('nama_lengkap');
        $karyawan->tempat_lahir = $request->get('tmp_lahir');
        $karyawan->tanggal_lahir = $request->get('tgl_lahir');
        $karyawan->nik = $user->nik;
        $karyawan->alamat = $request->get('userAlamat');
        $karyawan->company = $request->get('company');
        $karyawan->contact = $request->get('user-contact');
        $karyawan->jabatan = $request->get('jabatan');
        $karyawan->email = $request->get('user-email');
        $karyawan->branch = $request->get('branch');
        $karyawan->provinsi = $request->get('provinsi_name_user');
        $karyawan->kota = $request->get('kota_name_user');
        $karyawan->kecamatan = $request->get('kecamatan_name_user');
        $karyawan->kel_desa = $request->get('keldesa_name_user');
        $karyawan->gelar = $request->get('gelar');
        $karyawan->domisili =  $request->get('domisili');
        $karyawan->warga_negara = 'Indonesia';
        $karyawan->status = 'DRAFT';
        $karyawan->save();

        try {
            $action = new Actor();
            $action->users = $user->id;
            $action->content = $request->get('nama_lengkap');
            $action->action = 'Karyawan ' . $request->get('nama_lengkap') . ' Telah melakukan registrasi';
            $action->created_at = now();
            $action->save();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Data berhasil disimpan, tetapi gagal menyimpan tindakan',
                'errors' => ['action' => 'Gagal menyimpan tindakan: ' . $e->getMessage()]
            ], 500);
        }

        $phoneNumber = '+62' . ltrim($request->get('user-contact'), '0');
        $message = "Akun anda, {$request->get('nama_lengkap')} dengan username {$request->get('nik')} telah berhasil melakukan registrasi, mohon ditunggu untuk proses verifikasinya. Jika ada pertanyaan, silahkan hubungi costumer service kami. Terima Kasih.";

        sendFonteNotification($phoneNumber, $message);

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $karyawan
        ], 201);
    }

    public function getBranches($companyId)
    {
        $company = CompanyModel::with('branches')->find($companyId);

        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        $branchesArray = $company->branches->map(function ($branch) {
            return [
                'id' => $branch->id,
                'branch' => $branch->nama_cabang
            ];
        });

        return response()->json($branchesArray);
    }
}
