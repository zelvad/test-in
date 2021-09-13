<?php

namespace App\Http\Requests\Users;

use App\Services\Dto\Users\LoginUserDto;
use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required'
        ];
    }

    /**
     * @return LoginUserDto
     */
    public function getDto(): LoginUserDto
    {
        return new LoginUserDto(
            $this->get('email'),
            $this->get('password')
        );
    }
}
