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
Route::get('{name}/sells', 'HomeController@view_sell')->name('view_sell');
Route::get('{name}/products', 'HomeController@view_product')->name('view_product');
Route::get('{name}/services', 'HomeController@view_service')->name('view_service');
Route::get('{name}/singular_clients', 'HomeController@view_client_singular')->name('view_client_singular');
Route::get('{name}/enterprise_clients', 'HomeController@view_client_enterprise')->name('view_client_enterprise');

//create
Route::post('{name}/sells', 'HomeController@view_sell')->name('create_sell');
Route::post('{name}/products', 'HomeController@view_product')->name('create_product');
Route::post('{name}/services', 'HomeController@view_service')->name('create_service');
Route::post('{name}/singular_clients', 'HomeController@view_client_singular')->name('create_client_singular');
Route::post('{name}/enterprise_clients', 'HomeController@view_client_enterprise')->name('create_client_enterprise');