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

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api){
    // EndPoint devuelve fecha y hora del servidor
    $api->get('fecha-hora',
        function(){
            date_default_timezone_set('America/Bogota');
            $fecha_hora = [
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ];
            return $fecha_hora;
        }
    );

    // EndPoint para crear un usuario
    $api->post('register', 'App\Http\Controllers\Users\UserController@store');

    // EndPoint para logearse
    $api->post('auth', 'App\Http\Controllers\Auth\AuthenticationController@login');

    // EndPoint para crear una tienda
    $api->post('store/{nombre}', 'App\Http\Modulos\Tiendas\TiendaController@store');
    // EndPoint para ver una tienda
    $api->get('store/{nombre}', 'App\Http\Modulos\Tiendas\TiendaController@show');
    // EndPoint para eliminar una tienda
    $api->delete('store/{nombre}', 'App\Http\Modulos\Tiendas\TiendaController@destroy');
    // EndPoint para listar las tiendas
    $api->get('stores', 'App\Http\Modulos\Tiendas\TiendaController@list');

    // EndPoint para crear un articulo
    $api->post('item/{nombre}', 'App\Http\Modulos\Articulos\ArticuloController@store');
    // Grupo de rutas
    $api->group([
        'middleware' => ['modify.header', 'jwt.auth', 'bindings']
    ],
    function ($api) {
        // EndPoint para crear un articulo
        $api->get('item/{nombre}', 'App\Http\Modulos\Articulos\ArticuloController@show');
    });
    // EndPoint para crear un articulo
    $api->put('item/{nombre}', 'App\Http\Modulos\Articulos\ArticuloController@update');
    // EndPoint para eliminar un articulo
    $api->delete('item/{nombre}', 'App\Http\Modulos\Articulos\ArticuloController@destroy');
    // EndPoint para listar los articulos
    $api->get('items', 'App\Http\Modulos\Articulos\ArticuloController@list');

});