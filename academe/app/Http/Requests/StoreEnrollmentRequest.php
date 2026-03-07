<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'student_id' => ['required', 'integer'],
            'course_id'  => ['required', 'integer'],
        ];

        if (config('backend.mode') !== 'microservices') {
            $rules['student_id'][] = 'exists:students,id';
            $rules['course_id'][] = 'exists:courses,id';
        }

        return $rules;
    }
}
