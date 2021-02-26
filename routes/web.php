<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});
*/
Route::get('/phpinfo', function() {
    return phpinfo();
});
//to make storage link
Route::get('/storage_link', function () {
    return Artisan::call('storage:link');
});

Route::get('/', function () {
    return view('auth.login');
})->name('root');

//Company create
Route::get('new_company', 'Company\CompanyController@create')->name('create_company');
Route::post('new_company', 'Company\CompanyController@store')->name('store_company');

//Authentication
Auth::routes();

//end session
Route::post('/loggout', 'User\UserController@end_session')->name('end_session');

//Verification email
Auth::routes(['verify' => true]);

//Aplicar Middware de Authentication
//Route::get('Admin/{id}/Update', 'Company\CompanyController@edit')->name('edit_selected_company')->middleware('auth');
Route::get('/admin', function () {
    return view('pt.Admin.pages.login');
})->name('login_admin_view');
Route::post('/admin', 'Admin\LoginController@login')->name('login_admin');
Route::get('admin/companies', 'Company\CompanyController@index')->name('show_companies');
Route::get('admin/{id}', 'Company\CompanyController@show')->name('get_company');
Route::put('admin/{id}', 'Company\CompanyController@update')->name('save_update_company');
Route::delete('admin/{id}', 'Company\CompanyController@destroy')->name('remove_company');

//company (authenticated)
Route::get('/company', 'Company\CompanyController@show_company')->name('view_company');
Route::put('/company', 'Company\CompanyController@update_company')->name('edit_company');
Route::put('/company/payment', 'Company\CompanyController@payment')->name('company_payment');

//about
Route::get('/about', 'Company\CompanyController@show_about')->name('view_about');

//report invoice
Route::get('/report/invoices', 'Report\ReportController@index_invoices_report')->name('view_invoices_report');
Route::get('/report/invoice/{id}', 'Invoice\InvoiceController@see_invoice')->name('report_invoice');
Route::get('/report/invoices/print/{invoices}', 'Report\ReportController@print_invoices_report')->name('print_invoices_report');
Route::post('/report/invoices', 'Report\ReportController@index_invoices_report')->name('get_invoices_report');

//taxes report
Route::get('report/taxes', 'Report\ReportController@index_tax')->name('view_tax');
Route::get('report/taxes/print/{invoices}', 'Report\ReportController@print_tax')->name('print_tax');

//report cash_sales
Route::get('/report/cash_sales', 'Report\ReportController@index_cash_sales_report')->name('view_cash_sales_report');
Route::get('/report/cash_sale/{id}', 'CashSale\CashSaleController@see_cash_sale')->name('report_cash_sale');
Route::get('/report/print/cash_sales/{cash_sales}', 'Report\ReportController@print_cash_sales_report')->name('print_cash_sales_report');
Route::post('/report/cash_sales', 'Report\ReportController@index_cash_sales_report')->name('get_cash_sales_report');

//credit
Route::get('/credit', 'Report\ReportController@index_credit')->name('view_credit');
Route::get('/credit/{id}', 'Invoice\InvoiceController@see_invoice')->name('credit_invoice');
Route::get('/credit/print/{invoices}', 'Report\ReportController@print_credit')->name('print_credit');
Route::post('/credit', 'HomeController@view_credit')->name('get_credit');

//debit
Route::get('/debit', 'Report\ReportController@index_debit')->name('view_debit');
Route::get('/debit/{id}', 'Invoice\InvoiceController@see_invoice')->name('debit_invoice');
Route::get('/debit/print/{invoices}', 'Report\ReportController@print_debit')->name('print_debit');
Route::post('/debit', 'HomeController@view_debit')->name('get_debit');
Route::put('/debit/payment/{id}', 'Invoice\InvoiceController@pay_invoice')->name('pay_invoice');

//sale
Route::get('/sales', 'Sale\SaleController@create')->name('view_sale');
Route::post('/sales', 'Sale\SaleController@add_item')->name('store_sale'); //add item
Route::put('/sales', 'Sale\SaleController@add_item')->name('edit_sale'); //update item
Route::put('/sales/update', 'Sale\SaleController@edit_sale_item')->name('edit_sale_item'); //update item
Route::delete('/sales/remove', 'Sale\SaleController@remove_sale_item')->name('remove_sale_item');
Route::get('/sales/sell_invoice', 'Invoice\InvoiceController@store_invoice')->name('sell_invoice');
Route::get('/sales/sell_vd', 'CashSale\CashSaleController@store_vd')->name('sell_cash_sale');
Route::get('/sales/qoute', 'Sale\SaleController@quote')->name('quote');
Route::get('/sales/clean', 'Sale\SaleController@clean_sale')->name('clean_sale');


//invoice_note
Route::get('/invoice_notes', 'InvoiceNote\InvoiceNoteController@create')->name('view_invoice_note');
Route::get('/invoice_notes/invoice/{invoice}', 'InvoiceNote\InvoiceNoteController@select_invoice')->name('select_invoice');
Route::get('/invoice_notes/index', 'InvoiceNote\InvoiceNoteController@index')->name('index_invoice_note');
Route::get('/invoice_notes/store', 'InvoiceNote\InvoiceNoteController@store')->name('store_invoice_note');
Route::post('/invoice_notes/invoice/add', 'InvoiceNote\InvoiceNoteController@addItem')->name('add_invoice_note_item');
Route::get('/invoice_notes/update/{id}', 'InvoiceNote\InvoiceNoteController@edit')->name('edit_invoice_note');
Route::put('/invoice_notes/update/{id}', 'InvoiceNote\InvoiceNoteController@update')->name('update_invoice_note');
Route::get('/invoice_notes/print/{id}', 'InvoiceNote\InvoiceNoteController@print')->name('print_invoice_note');
Route::get('/invoice_notes/clean', 'InvoiceNote\InvoiceNoteController@clean')->name('clean_temp_note');
Route::delete('/invoice_notes/remove_item/{id}', 'InvoiceNote\InvoiceNoteController@remove_item')->name('remove_note_item');
Route::delete('/invoice_notes/destroy/{id}', 'InvoiceNote\InvoiceNoteController@destroy')->name('destroy_invoice_note');

//service
Route::get('/services', 'Service\ServiceController@create')->name('view_service');
Route::get('/services/index', 'Service\ServiceController@index')->name('index_service');
Route::post('/services', 'Service\ServiceController@store')->name('store_service');
Route::get('/services/update/{id}', 'Service\ServiceController@edit')->name('edit_service');
Route::put('/services/update/{id}', 'Service\ServiceController@update')->name('update_service');
Route::delete('/services/destroy/{id}', 'Service\ServiceController@destroy')->name('destroy_service');

//product
Route::get('/products', 'Product\ProductController@create')->name('view_product');
Route::get('/products/index', 'Product\ProductController@index')->name('index_product');
Route::post('/products', 'Product\ProductController@store')->name('store_product');
Route::get('/products/update/{id}', 'Product\ProductController@edit')->name('edit_product');
Route::put('/products/update/{id}', 'Product\ProductController@update')->name('update_product');
Route::delete('/products/destroy/{id}', 'Product\ProductController@destroy')->name('destroy_product');

//client_singular
Route::get('/clients_singular', 'ClientSingular\ClientSingularController@index')->name('view_client_singular');
Route::get('/clients_singular/{id}', 'ClientSingular\ClientSingularController@edit')->name('edit_client_singular');
Route::post('/clients_singular', 'ClientSingular\ClientSingularController@store')->name('store_client_singular');
Route::put('/clients_singular/update_name/{id}', 'ClientSingular\ClientSingularController@update_name')->name('edit_client_singular_name');
Route::put('/clients_singular/update_email/{id}', 'ClientSingular\ClientSingularController@update_email')->name('edit_client_singular_email');
Route::put('/clients_singular/update_nuit/{id}', 'ClientSingular\ClientSingularController@update_nuit')->name('edit_client_singular_nuit');
Route::put('/clients_singular/update_phone/{id}', 'ClientSingular\ClientSingularController@update_phone')->name('edit_client_singular_phone');
Route::put('/clients_singular/update_address/{id}', 'ClientSingular\ClientSingularController@update_address')->name('edit_client_singular_address');
Route::delete('/clients_singular/remove_client_singular/{id}', 'ClientSingular\ClientSingularController@destroy')->name('destroy_client_singular');

//client_enterprise
Route::get('/clients_enterprise', 'ClientEnterprise\ClientEnterpriseController@index')->name('view_client_enterprise');
Route::get('/clients_enterprise/{id}', 'ClientEnterprise\ClientEnterpriseController@edit')->name('edit_client_enterprise');
Route::post('/clients_enterprise', 'ClientEnterprise\ClientEnterpriseController@store')->name('store_client_enterprise');
Route::put('/clients_enterprise/update_name/{id}', 'ClientEnterprise\ClientEnterpriseController@update_name')->name('edit_client_enterprise_name');
Route::put('/clients_enterprise/update_email/{id}', 'ClientEnterprise\ClientEnterpriseController@update_email')->name('edit_client_enterprise_email');
Route::put('/clients_enterprise/update_nuit/{id}', 'ClientEnterprise\ClientEnterpriseController@update_nuit')->name('edit_client_enterprise_nuit');
Route::put('/clients_enterprise/update_phone/{id}', 'ClientEnterprise\ClientEnterpriseController@update_phone')->name('edit_client_enterprise_phone');
Route::put('/clients_enterprise/update_address/{id}', 'ClientEnterprise\ClientEnterpriseController@update_address')->name('edit_client_enterprise_address');
Route::delete('/clients_enterprise/remove_client_enterprise/{id}', 'ClientEnterprise\ClientEnterpriseController@destroy')->name('destroy_client_enterprise');

//user
Route::get('/users', 'User\UserController@index')->name('view_user');
Route::get('/users/{id}', 'User\UserController@edit')->name('edit_user');
Route::post('/users', 'User\UserController@store')->name('store_user');
Route::put('/users/update_name/{id}', 'User\UserController@update_name')->name('update_user_name');
Route::put('/users/update_email/{id}', 'User\UserController@update_email')->name('update_user_email');
Route::put('/users/update_phone/{id}', 'User\UserController@update_phone')->name('update_user_phone');
Route::put('/users/update_priviliege/{id}', 'User\UserController@update_privilege')->name('update_user_privilege');
Route::put('/users/update_address/{id}', 'User\UserController@update_address')->name('update_user_address');
Route::put('/users/update_gender/{id}', 'User\UserController@update_gender')->name('update_user_gender');
Route::put('/users/update_birthdate/{id}', 'User\UserController@update_birthdate')->name('update_user_birthdate');
Route::put('/users/update_password/{id}', 'User\UserController@update_password')->name('update_user_password');
Route::delete('/users/remove_user/{id}', 'User\UserController@destroy')->name('destroy_user');
