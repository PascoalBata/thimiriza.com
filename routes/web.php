<?php

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
Route::get('Admin/{id}/Update', 'Company\CompanyController@edit')->name('edit_company')->middleware('auth');
Route::get('Admin/Companies', 'Company\CompanyController@index')->name('show_all_companies');
Route::get('Admin/{id}', 'Company\CompanyController@show')->name('get_company')->middleware('auth');
Route::put('Admin/{id}', 'Company\CompanyController@update')->name('save_update_company')->middleware('auth');
Route::delete('Admin/{id}', 'Company\CompanyController@destroy')->name('remove_company')->middleware('auth');


Route::get('home', 'HomeController@index')->name('home');

//home_views
//Route::get('/sales', 'HomeController@view_sale')->name('view_sale');

//sevice
Route::get('/services', 'Service\ServiceController@index')->name('view_service');
Route::post('/services', 'Service\ServiceController@store')->name('store_service');
Route::get('/services/update/{id}', 'Service\ServiceController@edit')->name('edit_service');
Route::put('/services/update/{id}', 'Service\ServiceController@update')->name('update_service');
Route::delete('/services/destroy/{id}', 'Service\ServiceController@destroy')->name('destroy_service');

//product
Route::get('/products', 'Product\ProductController@index')->name('view_product');
Route::post('/products', 'Product\ProductController@store')->name('store_product');
Route::get('/products/update/{id}', 'Product\ProductController@edit')->name('edit_product');
Route::put('/products/update/{id}', 'Product\ProductController@update')->name('update_product');
Route::delete('/products/destroy/{id}', 'Product\ProductController@destroy')->name('destroy_product');





Route::get('/clients_enterprise', 'ClientEnterprise\ClientEnterpriseController@index')->name('view_client_enterprise');

Route::get('/company', 'company\CompanyController@show_company')->name('view_company');
Route::put('/company', 'Company\CompanyController@update_company')->name('edit_company');
Route::put('/company/payment', 'Company\CompanyController@payment')->name('company_payment');
Route::get('/about', 'Company\CompanyController@show_about')->name('view_about');
Route::get('/credit', 'Sale\SaleController@index_credit')->name('view_credit');
Route::get('/debit', 'Sale\SaleController@index_debit')->name('view_debit');
Route::get('/report', 'Sale\SaleController@index_report')->name('view_report');

Route::post('/credit', 'HomeController@view_credit')->name('get_credit');
Route::post('/debit', 'HomeController@view_debit')->name('get_debit');
Route::post('/report', 'HomeController@view_report')->name('get_report');

Route::get('/credit/{id}', 'Invoice\InvoiceController@see_invoice')->name('credit_invoice');
Route::get('/debit/{id}', 'Invoice\InvoiceController@see_invoice')->name('debit_invoice');
Route::get('/report/{id}', 'Invoice\InvoiceController@see_invoice')->name('report_invoice');

Route::get('/report/print/{invoices}', 'Report\ReportController@print_report')->name('print_report');
Route::get('/debit/print/{invoices}', 'Report\ReportController@print_debit')->name('print_debit');
Route::get('/credit/print/{invoices}', 'Report\ReportController@print_credit')->name('print_credit');

//invoice
Route::put('/debit/payment', 'Invoice\InvoiceController@invoice_payment')->name('invoice_payment');

//store
Route::post('/sales', 'Sale\SaleController@store')->name('store_sale');
Route::post('/services', 'Service\ServiceController@store')->name('store_service');
Route::post('/clients_singular', 'ClientSingular\ClientSingularController@store')->name('store_client_singular');
Route::post('/clients_enterprise', 'ClientEnterprise\ClientEnterpriseController@store')->name('store_client_enterprise');
Route::post('/users', 'User\UserController@store')->name('store_user');


//sale
Route::get('/sales', 'Sale\SaleController@create')->name('create_sale');
Route::put('/sales', 'Sale\SaleController@store')->name('edit_sale');
Route::put('/sales/update', 'Sale\SaleController@edit_sale_item')->name('edit_sale_item');
Route::delete('/sales/remove', 'Sale\SaleController@remove_sale_item')->name('remove_sale_item');
Route::post('/sales/sell', 'Sale\SaleController@sell')->name('sell');
Route::post('/sales/qoute', 'Sale\SaleController@quote')->name('quote');
Route::delete('/sales/clear', 'Sale\SaleController@clean_sale')->name('clean_sale');

//client_singular
Route::get('/clients_singular', 'ClientSingular\ClientSingularController@index')->name('view_client_singular');
Route::get('/clients_singular/{id}', 'ClientSingular\ClientSingularController@edit')->name('edit_client_singular');
Route::put('/clients_singular/update_name/{id}', 'ClientSingular\ClientSingularController@update_name')->name('edit_client_singular_name');
Route::put('/clients_singular/update_email/{id}', 'ClientSingular\ClientSingularController@update_email')->name('edit_client_singular_email');
Route::put('/clients_singular/update_nuit/{id}', 'ClientSingular\ClientSingularController@update_nuit')->name('edit_client_singular_nuit');
Route::put('/clients_singular/update_phone/{id}', 'ClientSingular\ClientSingularController@update_phone')->name('edit_client_singular_phone');
Route::put('/clients_singular/update_address/{id}', 'ClientSingular\ClientSingularController@update_address')->name('edit_client_singular_address');
Route::delete('/clients_singular/remove_client_singular/{id}', 'ClientSingular\ClientSingularController@destroy')->name('destroy_client_singular');

//client_enterprise
Route::put('/clients_enterprise/update_name', 'ClientEnterprise\ClientEnterpriseController@update_name')->name('edit_client_enterprise_name');
Route::put('/clients_enterprise/update_email', 'ClientEnterprise\ClientEnterpriseController@update_email')->name('edit_client_enterprise_email');
Route::put('/clients_enterprise/update_nuit', 'ClientEnterprise\ClientEnterpriseController@update_nuit')->name('edit_client_enterprise_nuit');
Route::put('/clients_enterprise/update_phone', 'ClientEnterprise\ClientEnterpriseController@update_phone')->name('edit_client_enterprise_phone');
Route::put('/clients_enterprise/update_address', 'ClientEnterprise\ClientEnterpriseController@update_address')->name('edit_client_enterprise_address');
Route::delete('/clients_enterprise/delete_client_enterprise', 'ClientEnterprise\ClientEnterpriseController@destroy')->name('remove_client_enterprise');

//user
Route::get('/users', 'User\UserController@index')->name('view_user');
Route::get('/users/{id}', 'User\UserController@edit')->name('edit_user');
Route::put('/users/update_name/{id}', 'User\UserController@update_name')->name('update_user_name');
Route::put('/users/update_email/{id}', 'User\UserController@update_email')->name('update_user_email');
Route::put('/users/update_phone/{id}', 'User\UserController@update_phone')->name('update_user_phone');
Route::put('/users/update_priviliege/{id}', 'User\UserController@update_privilege')->name('update_user_privilege');
Route::put('/users/update_address/{id}', 'User\UserController@update_address')->name('update_user_address');
Route::put('/users/update_gender/{id}', 'User\UserController@update_gender')->name('update_user_gender');
Route::put('/users/update_birthdate/{id}', 'User\UserController@update_birthdate')->name('update_user_birthdate');
Route::put('/users/update_password/{id}', 'User\UserController@update_password')->name('update_user_password');
Route::delete('/users/remove_user/{id}', 'User\UserController@destroy')->name('destroy_user');

