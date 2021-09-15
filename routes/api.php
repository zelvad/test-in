<?php

use App\Http\Controllers\PreviewController;
use App\Http\Controllers\UserController;
use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    /** Пользователь */
    $api->group([
        'prefix' => 'user'
    ], function (Router $api) {

        /** Гостевая группа */
        $api->group([
            'middleware' => 'guest:api'
        ], function (Router $api) {
            $api->post('login', [UserController::class, 'login']);
            $api->post('register', [UserController::class, 'register']);
        });

        /** Авторизированная группа */
        $api->group([
            'middleware' => 'auth:api'
        ], function (Router $api) {
            $api->get('me', [UserController::class, 'getUser']);
            $api->post('logout', [UserController::class, 'logout']);
        });
    });

    /** Превью */
    $api->group([
        'prefix' => 'previews'
    ], function (Router $api) {

        /** Авторизированная группа */
        $api->group([
            'middleware' => 'auth:api'
        ], function (Router $api) {
            $api->get('list', [PreviewController::class, 'index']);
        });

        /** Общедоступные */
        $api->post('create', [PreviewController::class, 'store'])
            ->middleware('throttle:createPreview');
    });
});
