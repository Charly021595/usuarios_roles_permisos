<?php

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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Prueba WSLD
Route::get('/prueba', 'ServiciosASMXController@traer_users')->name('prueba');
Route::post('/traer_usuarios', 'ServiciosASMXController@traer_users')->name('traer_usuarios');

//Usuarios
Route::post('/guardar_usuarios', 'UserController@guardar_usuarios')->name('guardar_usuarios');

//Roles
Route::get('/vista_roles', 'RolController@vista_roles')->name('vista_roles');
Route::post('/traer_permisos_roles', 'RolController@traer_permisos_roles')->name('traer_permisos_roles');
Route::post('/traer_roles', 'RolController@lista_roles')->name('traer_roles');
Route::post('/guardar_roles', 'RolController@guardar_roles')->name('guardar_roles');
Route::post('/buscar_roles', 'RolController@buscar_roles')->name('buscar_roles');
Route::post('/actualizar_rol', 'RolController@actualizar_roles')->name('actualizar_rol');

//Permisos
Route::get('/vista_permisos', 'PermisoController@vista_permisos')->name('vista_permisos');
Route::post('/traer_permisos', 'PermisoController@lista_permisos')->name('traer_permisos');
Route::post('/guardar_permisos', 'PermisoController@guardar_permisos')->name('guardar_permisos');
Route::post('/buscar_permisos', 'PermisoController@buscar_permisos')->name('buscar_permisos');
Route::post('/actualizar', 'PermisoController@actualizar_permisos')->name('actualizar_permiso');