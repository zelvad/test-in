<?php

namespace App\Services\Users;

use App\Models\User;
use App\Services\Dto\Users\CreateUserDto;
use Throwable;

class CreateUserCommand
{
    /**
     * Создание нового пользователя
     * @param CreateUserDto $dto
     * @return User
     * @throws Throwable
     */
    public function createUser(CreateUserDto $dto): User
    {
        $user = new User;
        $user->name = $dto->getName();
        $user->email = $dto->getEmail();
        $user->password = $dto->getPassword();
        $user->saveOrFail();

        return $user;
    }
}
