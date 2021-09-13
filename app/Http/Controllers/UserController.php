<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\LoginUserRequest;
use App\Services\Users\CreateUserCommand;
use App\Services\Users\LoginUserCommand;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Throwable;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Регистрация и авторизация нового пользователя
     * @param CreateUserRequest $request
     * @param CreateUserCommand $createUserCommand
     * @return JsonResponse
     * @throws Throwable
     */
    public function register(CreateUserRequest $request, CreateUserCommand $createUserCommand): JsonResponse
    {
        $createUserCommand->createUser($request->getCreateDto());

        return response()->json(['ok' => true]);
    }

    /**
     * Авторизация пользователя
     * @param LoginUserRequest $request
     * @param LoginUserCommand $command
     * @return JsonResponse
     */
    public function login(LoginUserRequest $request, LoginUserCommand $command): JsonResponse
    {
        if (!$loginArr = $command->loginUser($request->getDto()))
            return response()->json(['ok' => false], 401);

        return response()->json($loginArr);
    }

    /**
     * Выход из аккаунта
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::logout();
        return response()->json(['ok' => true]);
    }

    /**
     * Получение авторизированного пользователя
     * @return JsonResponse
     */
    public function getUser(): JsonResponse
    {
        return response()->json(Auth::user());
    }
}
