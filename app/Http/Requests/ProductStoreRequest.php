<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array { 
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'integer', 'min:1'],
        ];
    }
}
