<?php

namespace App\Services\Users;

use App\Services\Dto\Users\LoginUserDto;

class LoginUserCommand
{
    /**
     * Авторизация пользователя
     * @param LoginUserDto $dto
     * @return array|bool
     */
    public function loginUser(LoginUserDto $dto)
    {
        $credentials = $this->getCredentials(
            $dto->getEmail(), $dto->getPassword()
        );

        if (!$token = $this->getAuthToken($credentials))
            return false;

        return $this->successArr($token);
    }

    /**
     * Массив для авторизации
     * @param string $email
     * @param string $password
     * @return string[]
     */
    private function getCredentials(string $email, string $password): array
    {
        return [
            'email' => $email,
            'password' => $password
        ];
    }

    /**
     * Авторизация
     * @param array $credentials
     * @return bool|string
     */
    private function getAuthToken(array $credentials)
    {
        return auth()->attempt($credentials);
    }

    /**
     * Массив об ошибке
     * @return array
     */
    private function errorArr(): array
    {
        return ['ok' => false];
    }

    /**
     * Массив успешной авторизации
     * @param string $token
     * @return array
     */
    private function successArr(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
