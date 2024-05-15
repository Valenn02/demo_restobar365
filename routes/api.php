<?php

use Illuminate\Http\Request;

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

//menu
Route::get('menuGet', 'menuController@index');
Route::post('menunew', 'menuController@create');
Route::put('menuactua/{id}', 'menuController@update');
Route::delete('menuRemove/{id}', 'menuController@destroy');
//categoria menu
Route::get('menucate', 'categoriamenuController@index');
Route::post('menunewCate', 'categoriamenuController@create');
Route::put('menuactuaCate/{id}', 'categoriamenuController@update');
Route::delete('menuRemoveCate/{id}', 'categoriamenuController@destroy');

Route::post('/qr/generarqr', 'QrController@generarQr');
Route::post('/qr/verificarpago', 'QrController@generarToken');

Route::post('/qr/confirmaPago', 'QrController@confirmaPago');

Route::post('/whatsapp/envia','WhatsappController@envia');

