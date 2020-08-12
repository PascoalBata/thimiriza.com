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
    //return view('pt.Login.pages.login');
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

//Route Test
Route::get('Test', 'Company\CompanyController@new_company_id')->name('test');

//Authentication
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
