<?php

namespace App\Http\Requests\Previews;

use App\Services\Dto\Previews\CreatePreviewDto;
use Illuminate\Foundation\Http\FormRequest;

class CreatePreviewRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'url' => 'required|url'
        ];
    }

    public function getDto(): CreatePreviewDto
    {
        return new CreatePreviewDto(
            $this->get('url')
        );
    }
}
