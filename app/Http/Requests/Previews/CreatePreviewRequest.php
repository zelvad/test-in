<?php

namespace App\Http\Requests\Previews;

use App\Services\Dto\Previews\CreatePreviewDto;
use Illuminate\Foundation\Http\FormRequest;

class CreatePreviewRequest extends FormRequest
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
            'url' => 'required|url'
        ];
    }

    /**
     * @return CreatePreviewDto
     */
    public function getDto(): CreatePreviewDto
    {
        return new CreatePreviewDto(
            $this->get('url')
        );
    }
}
