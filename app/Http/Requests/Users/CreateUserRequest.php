<?php

namespace App\Http\Requests\Users;

use App\Services\Dto\Users\CreateUserDto;
use App\Services\Dto\Users\LoginUserDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8'
        ];
    }

    /**
     * @return CreateUserDto
     */
    public function getCreateDto(): CreateUserDto
    {
        return new CreateUserDto(
            $this->get('name'),
            $this->get('email'),
            $this->get('password')
        );
    }

    /**
     * @return LoginUserDto
     */
    public function getLoginDto(): LoginUserDto
    {
        return new LoginUserDto(
            $this->get('email'),
            $this->get('password')
        );
    }
}
