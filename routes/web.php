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


//Company
Route::get('registo', 'Company\CompanyController@create')->name('new_company');
Route::post('registo', 'Company\CompanyController@store')->name('save_new_company');

//Aplicar um Middware de Authentication
Route::get('Admin/{id}/Update', 'Company\CompanyController@edit')->name('edit_company')->middleware('auth');
Route::get('Admin/Companies', 'Company\CompanyController@index')->name('show_all_companies');
Route::get('Admin/{id}', 'Company\CompanyController@show')->name('get_company')->middleware('auth');
Route::put('Admin/{id}', 'Company\CompanyController@update')->name('save_update_company')->middleware('auth');
Route::delete('Admin/{id}', 'Company\CompanyController@destroy')->name('remove_company')->middleware('auth');


//Authentication
Auth::routes();

Route::get('home', 'HomeController@index')->name('home');

//home_views
Route::get('/sells', 'HomeController@view_sell')->name('view_sell');
Route::get('/products', 'HomeController@view_product')->name('view_product');
Route::get('/services', 'HomeController@view_service')->name('view_service');
Route::get('/clients_singular', 'HomeController@view_client_singular')->name('view_client_singular');
Route::get('/clients_enterprise', 'HomeController@view_client_enterprise')->name('view_client_enterprise');
Route::get('/users', 'HomeController@view_user')->name('view_user');

//store
Route::post('/sells', 'Sell\SellControler@store')->name('store_sell');
Route::post('/products', 'Product\ProductController@store')->name('store_product');
Route::post('/services', 'Service\ServiceController@store')->name('store_service');
Route::post('/clients_singular', 'ClientSingular\ClientSingularController@store')->name('store_client_singular');
Route::post('/clients_enterprise', 'ClientEnterprise\ClientEnterpriseController@store')->name('store_client_enterprise');
Route::post('/users', 'User\UserController@store')->name('store_user');

//edit
Route::post('/sells/edit', 'Sell\SellControler@store')->name('edit_sell');

//edit_sevice
Route::put('/services/update_name', 'Service\ServiceController@update_name')->name('edit_service_name');
Route::put('/services/update_description', 'Service\ServiceController@update_description')->name('edit_service_description');
Route::put('/services/update_price', 'Service\ServiceController@update_price')->name('edit_service_price');

//edit_product
Route::put('/products/update_name', 'Product\ProductController@update_name')->name('edit_product_name');
Route::put('/products/update_description', 'Product\ProductController@update_description')->name('edit_product_description');
Route::put('/products/update_quantity', 'Product\ProductController@update_quantity')->name('edit_product_quantity');
Route::put('/products/update_price', 'Product\ProductController@update_price')->name('edit_product_price');

//edit_client_singular
Route::put('/clients_singular/update_name', 'ClientSingular\ClientSingularController@update_name')->name('edit_client_singular_name');
Route::put('/clients_singular/update_email', 'ClientSingular\ClientSingularController@update_email')->name('edit_client_singular_email');
Route::put('/clients_singular/update_nuit', 'ClientSingular\ClientSingularController@update_nuit')->name('edit_client_singular_nuit');
Route::put('/clients_singular/update_phone', 'ClientSingular\ClientSingularController@update_phone')->name('edit_client_singular_phone');
Route::put('/clients_singular/update_address', 'ClientSingular\ClientSingularController@update_address')->name('edit_client_singular_address');

//edit_client_enterprise
Route::put('/clients_enterprise/update_name', 'ClientEnterprise\ClientEnterpriseController@update_name')->name('edit_client_enterprise_name');
Route::put('/clients_enterprise/update_email', 'ClientEnterprise\ClientEnterpriseController@update_email')->name('edit_client_enterprise_email');
Route::put('/clients_enterprise/update_nuit', 'ClientEnterprise\ClientEnterpriseController@update_nuit')->name('edit_client_enterprise_nuit');
Route::put('/clients_enterprise/update_phone', 'ClientEnterprise\ClientEnterpriseController@update_phone')->name('edit_client_enterprise_phone');
Route::put('/clients_enterprise/update_address', 'ClientEnterprise\ClientEnterpriseController@update_address')->name('edit_client_enterprise_address');

//edit_user
Route::put('/users/edit_name', 'User\UserController@update_name')->name('edit_user_name');
Route::put('/users/edit_email', 'User\UserController@update_email')->name('edit_user_email');
Route::put('/users/edit_phone', 'User\UserController@update_phone')->name('edit_user_phone');
Route::put('/users/edit_priviliege', 'User\UserController@update_priviliege')->name('edit_user_privilege');
Route::put('/users/edit_address', 'User\UserController@update_address')->name('edit_user_address');
Route::put('/users/edit_gender', 'User\UserController@update_gender')->name('edit_user_gender');
Route::put('/users/edit_birthdate', 'User\UserController@update_name')->name('edit_user_birthdate');

//remove_service
Route::delete('/services/delete_service', 'Service\ServiceController@destroy')->name('remove_service');

//remove_product
Route::delete('/products/delete_product', 'Product\ProductController@destroy')->name('remove_product');

//remove_client_enterprise
Route::delete('/clients_enterprise/delete_client_enterprise', 'ClientEnterprise\ClientEnterpriseController@destroy')->name('remove_client_enterprise');

//remove_client_singular
Route::delete('/clients_singular/delete_client_singular', 'ClientSingular\ClientSingularController@destroy')->name('remove_client_singular');

//remove_user
Route::delete('/users/remove_user', 'User\UserController@destroy')->name('remove_user');
