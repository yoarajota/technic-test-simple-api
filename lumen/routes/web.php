<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use FastRoute\Route;

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/menu', [
    'uses' => 'RegistersController@index'
]);

// $router->get('/download-files-customers', [
//     'uses' => 'CustomersController@downloadFile'
// ]);

$router->group(['middleware' => ['request_injections']], function () use ($router) {
    $router->get('/list-[{register}]', [
        'uses' => 'Controller@index'
    ]);

    $router->get('/form-{register}/{id}', [
        'uses' => 'Controller@show'
    ]);

    $router->get('/form-[{register}]', [
        'uses' => 'Controller@create'
    ]);

    $router->delete('/delete-{register}/{id}', [
        'uses' => 'Controller@delete'
    ]);

    $router->group(['middleware' => ['validators']], function () use ($router) {
        $router->post('/form-{register}/{id}', [ // update
            'uses' => 'Controller@update'
        ]);

        $router->post('/form-[{register}]', [ // store
            'uses' => 'Controller@store'
        ]);
    });
});
