<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', 'web\auth\LoginController@register');
Route::post('register/company/submit', 'web\register\RegisterController@registerCompany');
Route::post('register/user/submit', 'web\register\RegisterController@registerUsers');

Route::post('dashboard/dashboard', 'web\DashboardController@dashboard');
Route::post('dashboard/detailProses', 'web\DashboardController@detailProses');
Route::post('dashboard/chartDashboard', 'web\DashboardController@chartDashboard');
Route::post('dashboard/countPieData', 'web\DashboardController@countPieData');
Route::post('dashboard/activity', 'web\DashboardController@activity');

Route::post('master/company/getData', 'api\master\CompanyController@getData');
Route::post('master/company/submit', 'api\master\CompanyController@submit');
Route::post('master/company/delete', 'api\master\CompanyController@delete');
Route::post('master/company/confirmDelete ', 'api\master\CompanyController@confirmDelete');

Route::post('master/branch/getData', 'api\master\BranchController@getData');
Route::post('master/branch/submit', 'api\master\BranchController@submit');
Route::post('master/branch/delete', 'api\master\BranchController@delete');
Route::post('master/branch/confirmDelete ', 'api\master\BranchController@confirmDelete');

Route::post('master/price_pnbp/getData', 'api\master\PricePnbpController@getData');
Route::post('master/price_pnbp/submit', 'api\master\PricePnbpController@submit');
Route::post('master/price_pnbp/delete', 'api\master\PricePnbpController@delete');
Route::post('master/price_pnbp/confirmDelete ', 'api\master\PricePnbpController@confirmDelete');

Route::post('master/menu/getData', 'api\master\MenuController@getData');
Route::post('master/menu/submit', 'api\master\MenuController@submit');
Route::post('master/menu/delete', 'api\master\MenuController@delete');
Route::post('master/menu/confirmDelete ', 'api\master\MenuController@confirmDelete');

Route::post('master/roles/getData', 'api\master\RolesController@getData');
Route::post('master/roles/submit', 'api\master\RolesController@submit');
Route::post('master/roles/delete', 'api\master\RolesController@delete');
Route::post('master/roles/confirmDelete ', 'api\master\RolesController@confirmDelete');

Route::post('master/permission/getData', 'api\master\PermissionsController@getData');
Route::post('master/permission/submit', 'api\master\PermissionsController@submit');
Route::post('master/permission/delete', 'api\master\PermissionsController@delete');
Route::post('master/permission/confirmDelete ', 'api\master\PermissionsController@confirmDelete');
Route::post('master/permission/showMenu ', 'api\master\PermissionsController@showMenu');

Route::post('master/karyawan/getData', 'api\master\KaryawanController@getData');
Route::post('master/karyawan/submit', 'api\master\KaryawanController@submit');
Route::post('master/karyawan/delete', 'api\master\KaryawanController@delete');
Route::post('master/karyawan/confirmDelete ', 'api\master\KaryawanController@confirmDelete');

Route::post('master/saksi/getData', 'api\master\SaksiController@getData');
Route::post('master/saksi/submit', 'api\master\SaksiController@submit');
Route::post('master/saksi/delete', 'api\master\SaksiController@delete');
Route::post('master/saksi/confirmDelete ', 'api\master\SaksiController@confirmDelete');

Route::post('master/users/getData', 'api\master\UsersController@getData');
Route::post('master/users/submit', 'api\master\UsersController@submit');
Route::post('master/users/delete', 'api\master\UsersController@delete');
Route::post('master/users/confirmDelete ', 'api\master\UsersController@confirmDelete');
Route::post('master/users/showDataKaryawan ', 'api\master\UsersController@showDataKaryawan');

// Transaksi Invoice
Route::post('transaksi/invoice/getData', 'api\Transaksi\InvoiceController@getData');
Route::post('transaksi/invoice/submit', 'api\Transaksi\InvoiceController@submit');
Route::post('transaksi/invoice/delete', 'api\Transaksi\InvoiceController@delete');
Route::post('transaksi/invoice/confirmDelete', 'api\Transaksi\InvoiceController@confirmDelete');
Route::post('transaksi/invoice/confirm', 'api\Transaksi\InvoiceController@confirm');
Route::post('transaksi/invoice/showDataBatch', 'api\Transaksi\InvoiceController@showDataBatch');
Route::post('transaksi/invoice/getDataBatch', 'api\Transaksi\InvoiceController@getDataBatch');
Route::post('transaksi/invoice/detailBacth', 'api\Transaksi\InvoiceController@detailBacth');
Route::post('transaksi/invoice/exportDataInvoicing', 'api\Transaksi\InvoiceController@exportDataInvoicing');

// Report Daily
Route::post('report/report-daily/getData', 'api\report\ReportDailyController@getData');
Route::post('report/report-daily/exportExcel', 'api\report\ReportDailyController@exportExcel');

// Transaksi Payment Invoice
Route::post('transaksi/invoice-payment/getData', 'api\Transaksi\PaymentController@getData');
Route::post('transaksi/invoice-payment/submit', 'api\Transaksi\PaymentController@submit');
Route::post('transaksi/invoice-payment/delete', 'api\Transaksi\PaymentController@delete');
Route::post('transaksi/invoice-payment/confirmDelete ', 'api\Transaksi\PaymentController@confirmDelete');
Route::post('transaksi/invoice-payment/showDataInvoice ', 'api\Transaksi\PaymentController@showDataInvoice');
Route::post('transaksi/invoice-payment/confirm ', 'api\Transaksi\PaymentController@confirm');
Route::post('transaksi/invoice-payment/duplicate ', 'api\Transaksi\PaymentController@duplicate');
Route::post('transaksi/invoice-payment/getDataInv', 'api\Transaksi\PaymentController@getDataInv');
Route::post('transaksi/invoice-payment/showBukti', 'api\Transaksi\PaymentController@showBukti');

// request-certificate
Route::post('certificate/request-certificate/getData', 'api\certificate\RequestCertificateController@getData');
Route::post('certificate/request-certificate/getDataQuotationConfirm', 'api\certificate\RequestCertificateController@getDataQuotationConfirm');
Route::post('certificate/request-certificate/submit', 'api\certificate\RequestCertificateController@submit');
Route::post('certificate/request-certificate/delete', 'api\certificate\RequestCertificateController@delete');
Route::post('certificate/request-certificate/confirmDelete ', 'api\certificate\RequestCertificateController@confirmDelete');
Route::post('certificate/request-certificate/confirm ', 'api\certificate\RequestCertificateController@confirm');
Route::post('certificate/request-certificate/duplicate ', 'api\certificate\RequestCertificateController@duplicate');
Route::post('certificate/request-certificate/submitWarkah ', 'api\certificate\RequestCertificateController@submitWarkah');
Route::post('certificate/request-certificate/updateStatusKontrak ', 'api\certificate\RequestCertificateController@updateStatusKontrak');
Route::post('certificate/request-certificate/export', 'api\certificate\RequestCertificateController@export');
Route::post('certificate/request-certificate/downloadAll', 'api\certificate\RequestCertificateController@downloadAll');
// verifikasi-certificate
Route::post('certificate/verifikasi-certificate/getData', 'api\certificate\VerifikasiSertifikatController@getData');
Route::post('certificate/verifikasi-certificate/dashboard', 'api\certificate\VerifikasiSertifikatController@dashboard');
Route::post('certificate/verifikasi-certificate/getDataQuotationConfirm', 'api\certificate\VerifikasiSertifikatController@getDataQuotationConfirm');
Route::post('certificate/verifikasi-certificate/submit', 'api\certificate\VerifikasiSertifikatController@submit');
Route::post('certificate/verifikasi-certificate/delete', 'api\certificate\VerifikasiSertifikatController@delete');
Route::post('certificate/verifikasi-certificate/confirmDelete ', 'api\certificate\VerifikasiSertifikatController@confirmDelete');
Route::post('certificate/verifikasi-certificate/showDataUserNotaris ', 'api\certificate\VerifikasiSertifikatController@showDataUserNotaris');
Route::post('certificate/verifikasi-certificate/getDataUserNotaris ', 'api\certificate\VerifikasiSertifikatController@getDataUserNotaris');
Route::post('certificate/verifikasi-certificate/searchDataItem ', 'api\certificate\VerifikasiSertifikatController@searchDataItem');
Route::post('certificate/verifikasi-certificate/submitMinuta ', 'api\certificate\VerifikasiSertifikatController@submitMinuta');
Route::post('certificate/verifikasi-certificate/cariBiaya ', 'api\certificate\VerifikasiSertifikatController@cariBiaya');
Route::post('certificate/verifikasi-certificate/sendNotifikasi ', 'api\certificate\VerifikasiSertifikatController@sendNotifikasi');
Route::post('certificate/verifikasi-certificate/submitDelegate ', 'api\certificate\VerifikasiSertifikatController@submitDelegate');
Route::post('certificate/verifikasi-certificate/export ', 'api\certificate\VerifikasiSertifikatController@export');
Route::post('certificate/verifikasi-certificate/exportMinuta ', 'api\certificate\VerifikasiSertifikatController@exportMinuta');
Route::post('certificate/verifikasi-certificate/scanPDF ', 'api\certificate\VerifikasiSertifikatController@fungsiscanPDF');
Route::post('certificate/verifikasi-certificate/import ', 'api\certificate\VerifikasiSertifikatController@import');
Route::post('certificate/verifikasi-certificate/showDataUserSaksi ', 'api\certificate\VerifikasiSertifikatController@showDataUserSaksi');
Route::post('certificate/verifikasi-certificate/getDataUserSaksi ', 'api\certificate\VerifikasiSertifikatController@getDataUserSaksi');
Route::post('certificate/verifikasi-certificate/downloadAll ', 'api\certificate\VerifikasiSertifikatController@downloadAll');

// verifikasi-certificate-notaris
Route::post('certificate/verifikasi-certificate-notaris/getData', 'api\certificate\RequestCertificateNotarisController@getData');
Route::post('certificate/verifikasi-certificate-notaris/dashboard', 'api\certificate\RequestCertificateNotarisController@dashboard');
Route::post('certificate/verifikasi-certificate-notaris/getDataQuotationConfirm', 'api\certificate\RequestCertificateNotarisController@getDataQuotationConfirm');
Route::post('certificate/verifikasi-certificate-notaris/submit', 'api\certificate\RequestCertificateNotarisController@submit');
Route::post('certificate/verifikasi-certificate-notaris/delete', 'api\certificate\RequestCertificateNotarisController@delete');
Route::post('certificate/verifikasi-certificate-notaris/confirmDelete ', 'api\certificate\RequestCertificateNotarisController@confirmDelete');
Route::post('certificate/verifikasi-certificate-notaris/showDataUserNotaris ', 'api\certificate\RequestCertificateNotarisController@showDataUserNotaris');
Route::post('certificate/verifikasi-certificate-notaris/getDataUserNotaris ', 'api\certificate\RequestCertificateNotarisController@getDataUserNotaris');
Route::post('certificate/verifikasi-certificate-notaris/searchDataItem ', 'api\certificate\RequestCertificateNotarisController@searchDataItem');
Route::post('certificate/verifikasi-certificate-notaris/submitMinuta ', 'api\certificate\RequestCertificateNotarisController@submitMinuta');
Route::post('certificate/verifikasi-certificate-notaris/cariBiaya ', 'api\certificate\RequestCertificateNotarisController@cariBiaya');
Route::post('certificate/verifikasi-certificate-notaris/sendNotifikasi ', 'api\certificate\RequestCertificateNotarisController@sendNotifikasi');
Route::post('certificate/verifikasi-certificate-notaris/konfirmasiPembayaran ', 'api\certificate\RequestCertificateNotarisController@konfirmasiPembayaran');
Route::post('certificate/verifikasi-certificate-notaris/generateNumber ', 'api\certificate\RequestCertificateNotarisController@generateNumber');
Route::post('certificate/verifikasi-certificate-notaris/inputAktaSela ', 'api\certificate\RequestCertificateNotarisController@inputAktaSela');
Route::post('certificate/verifikasi-certificate-notaris/downloadAll ', 'api\certificate\RequestCertificateNotarisController@downloadAll');

Route::post('approval/perusahaan/getData', 'api\approval\AppCompanyController@getData');
Route::post('approval/perusahaan/submit', 'api\approval\AppCompanyController@submit');
Route::post('approval/perusahaan/delete', 'api\approval\AppCompanyController@delete');
Route::post('approval/perusahaan/confirmReject ', 'api\approval\AppCompanyController@confirmReject');

Route::post('approval/karyawan/getData', 'api\approval\AppKaryawanController@getData');
Route::post('approval/karyawan/submit', 'api\approval\AppKaryawanController@submit');
Route::post('approval/karyawan/submit/dataExcel', 'api\approval\AppKaryawanController@submitUserExcel');
Route::post('approval/karyawan/upload', 'api\approval\AppKaryawanController@uploadExcel');
Route::post('approval/karyawan/delete', 'api\approval\AppKaryawanController@delete');
Route::post('approval/karyawan/confirmReject ', 'api\approval\AppKaryawanController@confirmReject');

Route::post('activity/getData', 'api\activity\ActivityUser@getData');
Route::post('activity/getDetaildata', 'api\activity\ActivityUser@showDetail');
