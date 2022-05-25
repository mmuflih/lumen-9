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

$router->get('/ping', function () use ($router) {
    return response()->json(['pong' => 'OK']);
});

/** public route */
$router->group(['prefix' => '/api/v1'], function () use ($router) {
    $router->group(['prefix' => '/auth'], function () use ($router) {
        $router->post('/login', 'AuthController@login');
        $router->post('/social', 'AuthController@social');
        $router->post('/logout', 'AuthController@logout');
        $router->put('/refresh', 'AuthController@refresh');
        $router->post('/register', 'UserController@register');
        $router->post('/request-reset-password', 'UserController@requestResetPassword');
        $router->put('/reset-password', 'UserController@resetPassword');
    });

    $router->group(['prefix' => '/otp'], function () use ($router) {
        $router->group(['prefix' => '/email'], function () use ($router) {
            $router->post('/resend', 'OtpController@request');
            $router->put('/verify', 'OtpController@verify');
        });

        $router->group(['prefix' => '/wa', 'middleware' => 'auth'], function () use ($router) {
            $router->post('/request', 'OtpController@requestPhone');
            $router->post('/verify', 'OtpController@verifyPhone');
        });
    });
});

/** protected route */
$router->group(['prefix' => '/api/v1', 'middleware' => 'auth'], function () use ($router) {
    $router->group(['prefix' => '/auth'], function () use ($router) {
        $router->get('/me', 'AuthController@me');
        $router->put('/set-password', 'UserController@setPassword');
    });
});

/** admin */
$router->group(['prefix' => '/admin/v1', 'middleware' => 'auth:admin'], function () use ($router) {
    $router->group(['prefix' => '/user'], function () use ($router) {
        $router->post('/', 'UserController@addUserByAdmin');
        $router->get('/', 'UserController@listUserByAdmin');
        $router->get('/{user_id}', 'UserController@getUserByAdmin');
    });
});
