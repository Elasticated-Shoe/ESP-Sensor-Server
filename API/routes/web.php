<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Http\Controllers;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/test', function () use ($router) {
    return "test";
});

$router->get('/data', function () use ($router) {
    $flights = App\users::all();
    
    //return $flights->toJson();
    return $flights;
    //return response()->json( $flights );
});

$router->group(['prefix' => 'sensors'], function () use ($router) {
    $router->get('', 'MetadataController@filterById');
    $router->get('id/{id}', 'MetadataController@findById');
    $router->get('user/{user}', 'MetadataController@findByUser');
});