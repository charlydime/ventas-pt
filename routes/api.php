<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/Cliente/activos','ClienteController@activos');
Route::resource('Cliente','ClienteController',[ 'only'=>['show','update','index','store','seleccion']]);

Route::get('/Producto/activos','ProductoController@activos');
Route::resource('Producto','ProductoController',[ 'except'=>['create','edit',]]);

Route::get('/Ventas/recalcula/{idVenta}','VentaController@recalcula');
Route::get('/Ventas/listar','VentaController@listar');
Route::resource('Ventas','VentaController',[ 'except'=>['create','edit']]);

Route::get('/Detalle/recalcula/{idVenta}','DetalleController@recalcula');
Route::get('/Detalle/listar/{id}','DetalleController@listar');
Route::resource('Detalle','DetalleController',[ 'except'=>['craete','edit',]]);
