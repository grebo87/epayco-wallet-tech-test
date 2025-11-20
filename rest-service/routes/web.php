<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

// REST API endpoints that proxy to the internal SOAP service
$router->post('/api/registerClient', 'EpaycoController@registerClient');
$router->post('/api/checkBalance', 'EpaycoController@checkBalance');
$router->post('/api/rechargeWallet', 'EpaycoController@rechargeWallet');
$router->post('/api/pay', 'EpaycoController@pay');
$router->post('/api/confirmPayment', 'EpaycoController@confirmPayment');