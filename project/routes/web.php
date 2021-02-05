<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(
    ['prefix' => 'post'],
    function ($router) {
        $router->get('/', 'PostController@index');
        $router->post('/', 'PostController@store');

        $router->group(
            ['prefix' => '{postId}'],
            function ($router) {
                $router->get('/', 'PostController@show');
            }
        );
    }
);
