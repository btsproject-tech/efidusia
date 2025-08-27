<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'web\auth\LoginController@index');
Route::get('/login', 'web\auth\LoginController@index');
Route::post('/user/signIn', 'web\auth\LoginController@signIn');

Route::get('/register', 'web\register\RegisterController@index');
Route::get('company-branches/{companyId}', 'web\register\RegisterController@getBranches');

Route::get('/user/signOut', 'web\auth\LoginController@signOut');
Route::get('/dashboard', 'web\DashboardController@index');
// Route::post('dashboard/getdata', 'web\DashboardController@getdata');
Route::get('/dashboard/tes', 'web\DashboardController@tes');

Route::get('master/karyawan', 'web\master\KaryawanController@index');

Route::get('master/company', 'web\master\CompanyController@index');
Route::get('master/company/add', 'web\master\CompanyController@add');
Route::get('master/company/ubah', 'web\master\CompanyController@ubah');

Route::get('master/branch', 'web\master\BranchController@index');
Route::get('master/branch/add', 'web\master\BranchController@add');
Route::get('master/branch/ubah', 'web\master\BranchController@ubah');

Route::get('master/price_pnbp', 'web\master\PricePnbpController@index');
Route::get('master/price_pnbp/add', 'web\master\PricePnbpController@add');
Route::get('master/price_pnbp/ubah', 'web\master\PricePnbpController@ubah');

Route::get('master/menu', 'web\master\MenuController@index');
Route::get('master/menu/add', 'web\master\MenuController@add');
Route::get('master/menu/ubah', 'web\master\MenuController@ubah');

Route::get('master/roles', 'web\master\RolesController@index');
Route::get('master/roles/add', 'web\master\RolesController@add');
Route::get('master/roles/ubah', 'web\master\RolesController@ubah');

Route::get('master/permission', 'web\master\PermissionsController@index');
Route::get('master/permission/add', 'web\master\PermissionsController@add');
Route::get('master/permission/ubah', 'web\master\PermissionsController@ubah');

Route::get('master/karyawan', 'web\master\KaryawanController@index');
Route::get('master/karyawan/add', 'web\master\KaryawanController@add');
Route::get('master/karyawan/ubah', 'web\master\KaryawanController@ubah');

Route::get('master/saksi', 'web\master\SaksiController@index');
Route::get('master/saksi/add', 'web\master\SaksiController@add');
Route::get('master/saksi/ubah', 'web\master\SaksiController@ubah');

Route::get('master/users', 'web\master\UsersController@index');
Route::get('master/users/add', 'web\master\UsersController@add');
Route::get('master/users/ubah', 'web\master\UsersController@ubah');

Route::get('master/payment_term', 'web\master\PaymentTermController@index');
Route::get('master/payment_term/add', 'web\master\PaymentTermController@add');
Route::get('master/payment_term/ubah', 'web\master\PaymentTermController@ubah');

Route::get('transaksi/invoice', 'web\Transaksi\InvoiceController@index');
Route::get('transaksi/invoice/add', 'web\Transaksi\InvoiceController@add');
Route::get('transaksi/invoice/ubah', 'web\Transaksi\InvoiceController@ubah');
Route::get('transaksi/invoice/detail', 'web\Transaksi\InvoiceController@detail');

// Transaksi Invoice Payment
Route::get('transaksi/invoice-payment', 'web\Transaksi\PaymentController@index');
Route::get('transaksi/invoice-payment/add', 'web\Transaksi\PaymentController@add');
Route::get('transaksi/invoice-payment/ubah', 'web\Transaksi\PaymentController@ubah');
Route::get('transaksi/invoice-payment/detail', 'web\Transaksi\PaymentController@detail');

Route::get('report/report-daily', 'web\report\ReportDailyController@index');
Route::get('transaksi/shipping_review/add', 'web\Transaksi\ShipmentReviewController@add');
Route::get('transaksi/shipping_review/ubah', 'web\Transaksi\ShipmentReviewController@ubah');
Route::get('transaksi/shipping_review/detail', 'web\Transaksi\ShipmentReviewController@detail');

// request-certificate
Route::get('certificate/request-certificate', 'web\certificate\RequestCertificateController@index');
Route::get('certificate/request-certificate/add', 'web\certificate\RequestCertificateController@add');
Route::get('certificate/request-certificate/ubah', 'web\certificate\RequestCertificateController@ubah');
Route::get('certificate/request-certificate/detail', 'web\certificate\RequestCertificateController@detail');
// verifikasi-certificate
Route::get('certificate/verifikasi-certificate', 'web\certificate\VerifikasiSertifikatController@index');
Route::get('certificate/verifikasi-certificate/add', 'web\certificate\VerifikasiSertifikatController@add');
Route::get('certificate/verifikasi-certificate/ubah', 'web\certificate\VerifikasiSertifikatController@ubah');
Route::get('certificate/verifikasi-certificate/detail', 'web\certificate\VerifikasiSertifikatController@detail');
// verifikasi-certificate-notaris
Route::get('certificate/verifikasi-certificate-notaris', 'web\certificate\RequestCertificateNotarisController@index');
Route::get('certificate/verifikasi-certificate-notaris/add', 'web\certificate\RequestCertificateNotarisController@add');
Route::get('certificate/verifikasi-certificate-notaris/ubah', 'web\certificate\RequestCertificateNotarisController@ubah');
Route::get('certificate/verifikasi-certificate-notaris/detail', 'web\certificate\RequestCertificateNotarisController@detail');

Route::get('approval/perusahaan', 'web\approval\AppCompanyController@index');
Route::get('approval/perusahaan/add', 'web\approval\AppCompanyController@add');
Route::get('approval/perusahaan/ubah', 'web\approval\AppCompanyController@ubah');
Route::get('approval/perusahaan/detail', 'web\approval\AppCompanyController@detail');
Route::get('approval/perusahaan/download/{id}', 'web\approval\AppCompanyController@downloadFile');

Route::get('approval/karyawan', 'web\approval\AppKaryawanController@index');
Route::get('approval/karyawan/add', 'web\approval\AppKaryawanController@add');
Route::get('approval/karyawan/ubah', 'web\approval\AppKaryawanController@ubah');
Route::get('approval/karyawan/detail', 'web\approval\AppKaryawanController@detail');
Route::get('approval/karyawan/download', 'web\approval\AppKaryawanController@downloadTemplate');

Route::get('activity', 'web\activity\ActivityUser@index');
