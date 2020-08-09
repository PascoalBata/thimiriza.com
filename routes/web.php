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

Route::get('/', function () {
    return view('welcome');
});
*/
//thimiriza.com (login) -> raiz
//thimiriza.com/Conta -> criar_empresa (formulario)
//thimiriza.com/Admin/{id}/Dados -> ver_empresa
//thimiriza.com/Admin/{id}/Actualizar -> editar_empresa (formulario)
//thimiriza.com/Admin/Empresas -> listar_empresas
//
Route::get('/', function () {
    return view('pt.Login.pages.login');
})->name('raiz');

//EMPRESA
Route::get('Conta', 'Empresa\EmpresaController@create')->name('criar_empresa');
Route::post('Conta', 'Empresa\EmpresaController@store')->name('gravar_criar_empresa');
//Aplicar um Middware de Authentication
Route::get('Admin/{id}/Actualizar', 'Empresa\EmpresaController@edit')->name('editar_empresa')->middleware('auth');
Route::get('Admin/Empresas', 'Empresa\EmpresaController@index')->name('listar_empresas');
Route::get('Admin/{id}', 'Empresa\EmpresaController@show')->name('ver_empresa')->middleware('auth');
Route::put('Admin/{id}', 'Empresa\EmpresaController@update')->name('gravar_actualizar_empresa')->middleware('auth');
Route::delete('Admin/{id}', 'Empresa\EmpresaController@destroy')->name('remover_empresa')->middleware('auth');

//UTILIZADOR
//Route::post('')

//Route Test
Route::get('Teste', 'Empresa\EmpresaController@new_empresa_id')->name('teste');