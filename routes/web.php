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

Route::get('/home', 'HomeController@index')->middleware(['auth', 'can:admin.home'])->name('home');

//Prueba WSLD
Route::post('/traer_usuarios', 'ServiciosASMXController@traer_users')->middleware(['auth', 'can:admin.home'])->name('traer_usuarios');

//Usuarios
Route::post('/guardar_usuarios', 'UserController@guardar_usuarios')->middleware(['auth', 'can:admin.home'])->name('guardar_usuarios');

//API Usuarios
Route::post('/api/login_usuarios', 'UserController@login_api')->name('login_usuarios');

//Roles
Route::get('/vista_roles', 'RolController@vista_roles')->middleware(['auth', 'can:admin.roles.ver'])->name('vista_roles');
Route::post('/traer_permisos_roles', 'RolController@traer_permisos_roles')->middleware('auth')->name('traer_permisos_roles');
Route::post('/traer_roles', 'RolController@lista_roles')->middleware('auth')->name('traer_roles');
Route::post('/guardar_roles', 'RolController@guardar_roles')->middleware(['auth', 'can:admin.roles.crear'])->name('guardar_roles');
Route::post('/buscar_roles', 'RolController@buscar_roles')->middleware('auth')->name('buscar_roles');
Route::post('/actualizar_rol', 'RolController@actualizar_roles')->middleware(['auth', 'can:admin.roles.editar'])->name('actualizar_rol');

//Permisos
Route::get('/vista_permisos', 'PermisoController@vista_permisos')->middleware(['auth', 'can:admin.permisos.ver'])->name('vista_permisos');
Route::post('/traer_permisos', 'PermisoController@lista_permisos')->middleware('auth')->name('traer_permisos');
Route::post('/guardar_permisos', 'PermisoController@guardar_permisos')->middleware(['auth', 'can:admin.permisos.crear'])->name('guardar_permisos');
Route::post('/buscar_permisos', 'PermisoController@buscar_permisos')->middleware('auth')->name('buscar_permisos');
Route::post('/actualizar', 'PermisoController@actualizar_permisos')->middleware(['auth', 'can:admin.permisos.editar'])->name('actualizar_permiso');