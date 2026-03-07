<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name'  => 'required|string',
            'email' => ['required', 'email'],
        ];

        if (config('backend.mode') !== 'microservices') {
            $rules['email'][] = 'unique:students';
        }

        return $rules;
    }
}
