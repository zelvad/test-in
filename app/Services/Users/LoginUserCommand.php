<?php

namespace App\Services\Users;

use App\Services\Dto\Users\LoginUserDto;

class LoginUserCommand
{
    public function loginUser(LoginUserDto $dto)
    {
        $credentials = $this->getCredentials(
            $dto->getEmail(), $dto->getPassword()
        );

        if (!$token = $this->getAuthToken($credentials)) {
            return false;
        }

        return $this->successResponseArray($token);
    }

    private function getCredentials(string $email, string $password): array
    {
        return [
            'email' => $email,
            'password' => $password
        ];
    }

    private function getAuthToken(array $credentials)
    {
        return auth()->attempt($credentials);
    }

    private function successResponseArray(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
