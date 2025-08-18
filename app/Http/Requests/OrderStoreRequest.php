<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); } 

    public function rules(): array { 
        return [
            'product_id'   => ['required', 'exists:products,id'],
            'address'      => ['required', 'string', 'max:255'],
            // total_amount ni backendda mahsulot narxidan olamiz — foydalanuvchi o‘zgartira olmaydi
        ];
    }
}
