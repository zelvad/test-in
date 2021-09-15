<?php

namespace App\Http\Requests\Users;

use App\Services\Dto\Users\CreateUserDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8'
        ];
    }

    public function getCreateDto(): CreateUserDto
    {
        return new CreateUserDto(
            $this->get('name'),
            $this->get('email'),
            $this->get('password')
        );
    }
}
