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

$router->group(['prefix' => 'sensors/metadata', 'middleware' => ['jwt.auth']], function () use ($router) {
    $router->put('new', 'MetadataController@createSensorMetadata');
    $router->post('id/{id}', 'MetadataController@updateSensorMetadata');
    $router->delete('id/{id}', 'MetadataController@deleteSensorMetadata');

    $router->get('', 'MetadataController@findByUser');
});
$router->group(['prefix' => 'sensors/data', 'middleware' => ['jwt.auth']], function () use ($router) {
    $router->put('', 'DataController@createReading');
    $router->put('batch', 'DataController@batchCreateReadings');
    $router->get('', 'DataController@getReadings');
});
$router->group(['prefix' => 'authenticate'], function () use ($router) {
    $router->post('', 'AuthController@getToken');
    $router->get('', ['uses' => 'AuthController@testToken', 'middleware' => 'jwt.auth']);
});