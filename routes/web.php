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
Route::post('/products/edit', 'Product\ProductController@store')->name('edit_product');
Route::put('/services/update_name', 'Service\ServiceController@update_name')->name('edit_service_name');
Route::put('/services/update_description', 'Service\ServiceController@update_description')->name('edit_service_description');
Route::put('/services/update_price', 'Service\ServiceController@update_price')->name('edit_service_price');
Route::post('/clients_singular/edit', 'ClientSingular\ClientSingularController@store')->name('edit_client_singular');
Route::post('/clients_enterprise', 'ClientEnterprise\ClientEnterpriseController@store')->name('edit_client_enterprise');
Route::post('/users/edit', 'User\UserController@store')->name('edit_user');