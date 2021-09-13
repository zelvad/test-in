<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class GetUsersListCommand
{
    /**
     * Получение списка пользователей
     * @return User[]|Collection
     */
    public function getUsersList()
    {
        return User::all();
    }
}
