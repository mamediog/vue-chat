<?php

$router->group(['prefix' => '/'], function ($router) {
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    $router->group(['prefix' => '/auth'], function ($router) {
        $router->post('/register','AuthController@create');
        $router->get('/hasuser','AuthController@hasUser');
        
        $router->get('/verify/{token}','AuthController@getEmailByToken');
        $router->post('/login','AuthController@login');
        $router->get('/verify','AuthController@verify');
    });

});

