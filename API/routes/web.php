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

$router->group(['prefix' => 'sensors'], function () use ($router) {
    $router->put('new', 'MetadataController@createSensorMetadata');
    $router->post('id/{id}', 'MetadataController@updateSensorMetadata');
    $router->delete('id/{id}', 'MetadataController@deleteSensorMetadata');

    // return array of matches
    $router->get('', 'MetadataController@filterById');
    $router->get('user/{user}', 'MetadataController@findByUser');

    // return single object
    $router->get('id/{id}', 'MetadataController@findById');
});