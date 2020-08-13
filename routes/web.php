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


//EMPRESA
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

Route::get('home/', 'HomeController@index')->name('home');

//Admin
Route::get('Admin/{name}/sells', 'HomeController@sell')->name('admin_sells');


/*
Route::get('home/{id}/sells', 'HomeController@sell')->name('sells');
Route::get('home/{id}/singular_clients', 'HomeController@about')->name('client_singular');
Route::get('home/{id}/enterprise_clients', 'HomeController@about')->name('client_enterprise');
Route::get('home/{id}/products', 'HomeController@about')->name('products');
Route::get('home/{id}/services', 'HomeController@about')->name('services');
Route::get('home/{id}/company', 'HomeController@about')->name('company');
Route::get('home/{id}/update_company', 'HomeController@about')->name('company_update');
*/