<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); } 

    public function rules(): array { 
        return [
            'name'        => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
