<?php

$router->group(['prefix' => '/'], function ($router) {
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    $router->group(['prefix' => '/auth'], function ($router) {
        $router->post('/login', 'AuthController@login');
        $router->post('/register','UserController@create');
        $router->get('/islogged','AuthController@isLogged');
        $router->get('/hasuser','UserController@hasUser');
    });

    $router->group(['middleware' => ['auth']], function ($router) {
        $router->get('/users','UserController@index');
    });

});
