<?php

namespace App\Http\Requests\Users;

use App\Services\Dto\Users\LoginUserDto;
use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required'
        ];
    }

    public function getDto(): LoginUserDto
    {
        return new LoginUserDto(
            $this->get('email'),
            $this->get('password')
        );
    }
}
