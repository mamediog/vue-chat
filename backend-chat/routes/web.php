<?php

$router->group(['prefix' => '/'], function ($router) {
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    
    /**
     * Rotas com prefixo AUTH
     */
    $router->group(['prefix' => '/auth'], function ($router) {
        $router->post('/login', 'AuthController@login');
        $router->post('/register','UserController@create');
        $router->get('/hasuser','UserController@hasUser');


        $router->group(['middleware' => ['auth']], function ($router) {
            $router->post('/logout','AuthController@logout');
            $router->get('/islogged','AuthController@isLogged');

            // ROTA PARA TESTES
            $router->get('/users','UserController@users');
        });
    });

    /**
     * Rotas com prefixo USER
     */
    $router->group(['prefix' => '/user', 'middleware' => ['auth']], function ($router) {
        $router->post('/search','UserController@findUsers');
        $router->post('/addfriend/{id}','UserController@addFriend');
        $router->get('/findfriends/{id}','UserController@searchFriends');
    });


    /**
     * Rotas com prefixo CHAT
     */
    // $router->group(['prefix' => '/chat', 'middleware' => ['auth']], function ($router) {
    //     $router->post('/create','ChatController@create');
    // });
    $router->post('/chat/create','ChatController@create');

});
