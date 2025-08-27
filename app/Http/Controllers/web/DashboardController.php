<?php

namespace App\Http\Controllers\web;

use App\RequestCertificate;
use App\Models\Master\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpParser\Node\Stmt\Foreach_;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi\Quotation;
use App\RequestCertificateContract;
use App\Http\Controllers\Controller;
use App\Models\Transaksi\ShippingExcecution;
use App\Models\Transaksi\ShippingInstruction;

class DashboardController extends Controller
{
    private $userGroup;
    private $id_user;
    private $nik;
    public $akses_menu = [];

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->middleware(function ($request, $next) {
            $this->akses_menu = json_decode(session('akses_menu'));
            return $next($request);
        });

        $this->userGroup = session('akses');
        $this->id_user = session('user_id');
        $this->nik = session('nik');
    }

    public function getHeaderCss()
    {
        return array(
            'js-1' => asset('assets/js/controllers/dashboard.js'),
        );
    }

    public function getTitleParent()
    {
        return "Monitoring";
    }

    public function getTableName()
    {
        return "";
    }

    public function index()
    {
        $data['data'] = [];
        $data['total_sk'] = count($this->getSkDraft());
        $data['total_si'] = count($this->getSkApproved());
        // $data['total_sk_draft'] = count($this->getSkDraft(['DRAFT']));
        // $data['total_sk_approved'] = count($this->getSkApproved('APPROVE'));
        // $data['total_sk_done'] = count($this->getSkFinished(['DONE']));
        // $data['total_sk_finished'] = count($this->getSkFinished(['FINISHED']));
        // $data['total_sk_complete'] = count($this->getSkFinished(['COMPLETE']));
        // $data['total_sk_finished'] = count($this->getSkFinished(['COMPLETE', 'FINISHED', 'DONE']));
        // $data['data_activity'] = $this->getDataActivity();
        // $data['last_transaction'] = $this->lastQuotation();
        $data['akses'] = $this->akses_menu;
        // dd($data['akses']);
        $view = view('web.dashboard.index', $data);
        $put['title_content'] = 'Dashboard';
        $put['title_top'] = 'Dashboard';
        $put['title_parent'] = $this->getTitleParent();
        $put['view_file'] = $view;
        $put['header_data'] = $this->getHeaderCss();
        return view('web.template.main', $put);
    }

    public function getSkDraft($status = '')
    {
        $datadb = RequestCertificateContract::with(['RequestContract'])
            ->whereNull('deleted')
            ->whereHas('RequestContract', function ($query) {
                $query->whereNull('deleted');
            });

        if ($this->userGroup == 'finance') {
            if ($status != '') {
                $datadb->whereIn('request_sertificate_contract.status', is_array($status) ? $status : [$status]);
            }
            $datadb->where('request_sertificate_contract.creator', $this->id_user);
        }

        if ($this->userGroup == 'vendor') {
            if ($status != '') {
                $datadb->whereIn('request_sertificate_contract.status', is_array($status) ? $status : [$status]);
            }
            $datadb->where('request_sertificate_contract.updater', $this->id_user);
        }

        if ($this->userGroup == 'notaris') {
            if ($status != '') {
                $datadb->whereIn('request_sertificate_contract.status', is_array($status) ? $status : [$status]);
            }
            $datadb->where('delegate_to', session('nik'));
        }

        if (strcasecmp($this->userGroup, 'SUPERADMIN') == 0) {
            if ($status != '') {
                $datadb->whereIn('request_sertificate_contract.status', is_array($status) ? $status : [$status]);
            }
        }

        $datadb = $datadb->get()->toArray();
        // dd(session('nik'));
        return $datadb;
    }

    public function getSkApproved($status = '')
    {
        $datadb = RequestCertificateContract::with(['RequestContract'])
            ->whereNull('deleted')
            ->whereHas('RequestContract', function ($query) {
                $query->whereNull('deleted');
            });

        if ($this->userGroup == 'finance') {
            if ($status != '') {
                $datadb->whereIn('request_sertificate_contract.status', is_array($status) ? $status : [$status]);
            }
            $datadb->where('request_sertificate_contract.creator', $this->id_user);
        }

        if ($this->userGroup == 'vendor') {
            if ($status != '') {
                $datadb->whereIn('request_sertificate_contract.status', is_array($status) ? $status : [$status]);
            }
            $datadb->where('request_sertificate_contract.updater', $this->id_user);
        }

        if ($this->userGroup == 'notaris') {
            if ($status != '') {
                $datadb->whereIn('request_sertificate_contract.status', is_array($status) ? $status : [$status]);
            }
            $datadb->where('delegate_to', session('nik'));
        }

        if (strcasecmp($this->userGroup, 'SUPERADMIN') == 0) {
            if ($status != '') {
                $datadb->whereIn('request_sertificate_contract.status', is_array($status) ? $status : [$status]);
            }
        }

        $datadb = $datadb->get()->toArray();
        // dd($datadb);
        return $datadb;
    }

    public function getSkFinished($status = '')
    {
        // $data['userGroup'] = session('akses');
        // $data['id_user'] = session('user_id');

        $datadb = RequestCertificateContract::with(['RequestContract'])
            ->whereNull('deleted')
            ->whereHas('RequestContract', function ($query) {
                $query->whereNull('deleted');
            });

        if ($this->userGroup == 'finance') {
            if ($status != '') {
                $datadb->whereIn('request_sertificate_contract.status', is_array($status) ? $status : [$status]);
            }
            $datadb->where('request_sertificate_contract.creator', $this->id_user);
        }

        if ($this->userGroup == 'vendor') {
            if ($status != '') {
                $datadb->whereIn('request_sertificate_contract.status', is_array($status) ? $status : [$status]);
            }
            $datadb->where('request_sertificate_contract.updater', $this->id_user);
        }

        if ($this->userGroup == 'notaris') {
            if ($status != '') {
                $datadb->whereIn('request_sertificate_contract.status', is_array($status) ? $status : [$status]);
            }
            $datadb->where('delegate_to', session('nik'));
        }

        if (strcasecmp($this->userGroup, 'SUPERADMIN') == 0) {
            if ($status != '') {
                $datadb->whereIn('request_sertificate_contract.status', is_array($status) ? $status : [$status]);
            }
        }

        $datadb = $datadb->get()->toArray();
        // dd($datadb);
        return $datadb;
    }

    public function activity(Request $request)
    {
        $startDate = $request->input('tgl_awal') ? Carbon::parse($request->input('tgl_awal')) : now()->startOfMonth();
        $endDate = $request->input('tgl_akhir') ? Carbon::parse($request->input('tgl_akhir'))->endOfDay() : now()->endOfDay();
        $currentYear = now()->year;

        $datadb = DB::table('actors as a')
            ->select('a.action', 'a.created_at', 'k.nama_lengkap as karyawan_name')
            ->join('users as u', 'u.id', '=', 'a.users')
            ->leftJoin('karyawan as k', 'k.nik', '=', 'u.nik')
            ->whereNull('a.deleted')
            ->whereYear('a.created_at', $currentYear)
            ->whereBetween('a.created_at', [$startDate, $endDate])
            ->orderBy('a.id', 'desc');

        if (session('akses') == 'vendor') {
            $datadb->where('a.users', $this->id_user);
        }
        if (session('akses') == 'notaris') {
            $datadb->where('a.users', $this->id_user);
        }
        if (session('akses') == 'finance') {
            $datadb->where('a.users', $this->id_user);
        }
        if (session('akses') == 'admin minuta') {
            $datadb->where('a.users', $this->id_user);
        }
        $data['data'] = $datadb->limit(10)->get()->toArray();

        return response()->json($data['data']);
    }

    public function dashboard(Request $request)
    {
        $data = $request->all();
        DB::enableQueryLog();
        $data['data'] = [];
        $data['recordsTotal'] = 0;
        $data['recordsFiltered'] = 0;
        $startDate = $request->input('tgl_awal') ? Carbon::parse($request->input('tgl_awal')) : now()->startOfMonth();
        $endDate = $request->input('tgl_akhir') ? Carbon::parse($request->input('tgl_akhir'))->endOfDay() : now()->endOfDay();
        $tahunAwal = $startDate ? Carbon::parse($startDate)->year : now()->year;
        $tahunAkhir = $endDate ? Carbon::parse($endDate)->year : now()->year;

        $datadb = RequestCertificateContract::with(['UserDelegate', 'RequestContract', 'RequestContractStatusDailyReport', 'RequestContract.Creator.Karyawan', 'DataMinuta'])
            ->whereNull('deleted')
            ->whereHas('RequestContract', function ($query) {
                $query->whereNull('deleted');
            })
            ->whereBetween(DB::raw('YEAR(created_at)'), [$tahunAwal, $tahunAkhir])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('id', 'desc');

        if (session('akses') == 'notaris') {
            $datadb->whereHas('RequestContract', function ($query) {
                $query->where('delegate_to', session('nik'));
            });
        }

        if (session('akses') == 'vendor') {
            $datadb->where(function ($query) {
                $query->where('updater', session('user_id'));
            });
        }

        if (session('akses') == 'finance') {
            $datadb->where(function ($query) {
                $query->where('creator', session('user_id'));
            });
        }

        if (isset($_POST)) {
            $data['recordsTotal'] = $datadb->get()->count();
            if (isset($_POST['search']['value'])) {
                $keyword = $_POST['search']['value'];
                $datadb->where(function ($query) use ($keyword) {
                    $query->where('debitur', 'LIKE', '%' . $keyword . '%');
                });
            }

            if (isset($_POST['order'][0]['column'])) {
                $datadb->orderBy('id', $_POST['order'][0]['dir']);
            }

            $data['recordsFiltered'] = $datadb->get()->count();

            if (isset($_POST['length'])) {
                $datadb->limit($_POST['length']);
            }
            if (isset($_POST['start'])) {
                $datadb->offset($_POST['start']);
            }
        }

        $data['data'] = $datadb->get()->toArray();
        $data['draw'] = $_POST['draw'];
        return json_encode($data);
    }

    public function detailProses(Request $request)
    {
        $data = $request->all();
        $data['data'] = DB::table('request_sertificate_contract_status as rscs')
            ->where('rscs.request_sertificate_contract', $data['id'])
            ->whereNull('rscs.deleted')
            ->orderBy('rscs.id', 'asc')
            ->get()
            ->toArray();
        $data['last_status'] = DB::table('request_sertificate_contract_status as rscs')
            ->where('rscs.request_sertificate_contract', $data['id'])
            ->whereNull('rscs.deleted')
            ->orderBy('rscs.id', 'desc')
            ->first();
        // dd($data['data']);
        return view('web.dashboard.modal.detailProses', $data);
    }

    public function chartPieData($status = '', $tahunAwal = '', $tahunAkhir = '', $startDate = '', $endDate = '')
    {

        $datadb = RequestCertificateContract::with(['RequestContract'])
            ->whereNull('deleted')
            ->whereHas('RequestContract', function ($query) {
                $query->whereNull('deleted');
            });

        if ($this->userGroup == 'finance') {
            if ($status != '') {
                $datadb->whereIn('request_sertificate_contract.status', is_array($status) ? $status : [$status]);
            }
            $datadb->where('request_sertificate_contract.creator', $this->id_user);
        }

        if ($this->userGroup == 'vendor') {
            if ($status != '') {
                $datadb->whereIn('request_sertificate_contract.status', is_array($status) ? $status : [$status]);
            }
            $datadb->where('request_sertificate_contract.updater', $this->id_user);
        }

        if ($this->userGroup == 'notaris') {
            if ($status != '') {
                $datadb->whereIn('request_sertificate_contract.status', is_array($status) ? $status : [$status]);
            }
            $datadb->where('delegate_to', session('nik'));
        }

        if (strcasecmp($this->userGroup, 'SUPERADMIN') == 0) {
            if ($status != '') {
                $datadb->whereIn('request_sertificate_contract.status', is_array($status) ? $status : [$status]);
            }
        }

        $datadb = $datadb
            ->whereBetween(DB::raw('YEAR(created_at)'), [$tahunAwal, $tahunAkhir])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->toArray();
        return $datadb;
    }

    public function chartDashboard(Request $request)
    {
        $startDate = $request->input('tgl_awal') ? Carbon::parse($request->input('tgl_awal')) : now()->startOfMonth();
        $endDate = $request->input('tgl_akhir') ? Carbon::parse($request->input('tgl_akhir'))->endOfDay() : now()->endOfDay();
        $tahunAwal = $startDate ? Carbon::parse($startDate)->year : now()->year;
        $tahunAkhir = $endDate ? Carbon::parse($endDate)->year : now()->year;

        $data = DB::table('request_sertificate_contract')
            ->select(DB::raw('DATE(created_at) as tanggal, COUNT(id) as jumlah_data'))
            ->whereNull('deleted')
            ->whereBetween(DB::raw('YEAR(created_at)'), [$tahunAwal, $tahunAkhir])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal');

        if (session('akses') == 'notaris') {
            $data->where('delegate_to', session('nik'));
        } elseif (session('akses') == 'vendor') {
            $data->where('updater', session('user_id'));
        } elseif (session('akses') == 'finance') {
            $data->where('creator', session('user_id'));
        }

        $data = $data->get()->keyBy('tanggal');

        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            new \DateTime($endDate->copy()->addDay())
        );

        $result = [];
        foreach ($period as $date) {
            $tanggal = $date->format('Y-m-d');
            $result[] = [
                'tanggal' => $tanggal,
                'jumlah_data' => $data->has($tanggal) ? $data[$tanggal]->jumlah_data : 0,
            ];
        }

        return response()->json($result);
    }


    public function countPieData(Request $request)
    {
        $startDate = $request->input('tgl_awal') ? Carbon::parse($request->input('tgl_awal')) : now()->startOfMonth();
        $endDate = $request->input('tgl_akhir') ? Carbon::parse($request->input('tgl_akhir'))->endOfDay() : now()->endOfDay();
        $tahunAwal = $startDate ? Carbon::parse($startDate)->year : now()->year;
        $tahunAkhir = $endDate ? Carbon::parse($endDate)->year : now()->year;

        $data = [];
        $data['total_sk_draft'] = count($this->chartPieData(['DRAFT'], $tahunAwal, $tahunAkhir, $startDate, $endDate));
        $data['total_sk_approved'] = count($this->chartPieData('APPROVE', $tahunAwal, $tahunAkhir, $startDate, $endDate));
        $data['total_sk_done'] = count($this->chartPieData(['DONE'], $tahunAwal, $tahunAkhir, $startDate, $endDate));
        $data['total_sk_finished'] = count($this->chartPieData(['FINISHED'], $tahunAwal, $tahunAkhir, $startDate, $endDate));
        $data['total_sk_complete'] = count($this->chartPieData(['COMPLETE'], $tahunAwal, $tahunAkhir, $startDate, $endDate));
        $dataChartPie = [
            $data['jumlah_data'] = [$data['total_sk_draft'], $data['total_sk_approved'], $data['total_sk_done'], $data['total_sk_finished'], $data['total_sk_complete']],
            $data['labels'] = ['DRAFT', 'APPROVE', 'DONE', 'FINISHED', 'COMPLETE'],
            $data['total_sk'] = count($this->chartPieData('', $tahunAwal, $tahunAkhir, $startDate, $endDate)),
        ];
        return response()->json($dataChartPie);
    }
}
