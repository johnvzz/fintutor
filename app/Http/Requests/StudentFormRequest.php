<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class StudentFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $student = $this->route('student');
        $studentId = $student instanceof User
            ? $student->getKey()
            : $this->user()?->getAuthIdentifier();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($studentId),
            ],
            'phone' => ['required', 'string', 'max:20', 'regex:/^\+?[0-9-]+$/'],
            'password' => ['sometimes', 'nullable', 'string', 'min:8', 'max:255'],
            'dob' => ['sometimes', 'nullable', 'date_format:d-m-Y'],
            'grade' => ['sometimes', 'nullable', 'string', 'max:20'],
            'school' => ['sometimes', 'nullable', 'string', 'max:255'],
            'parent_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'parent_phone' => ['sometimes', 'nullable', 'string', 'max:20', 'regex:/^\+?[0-9-]+$/'],
        ];
    }
}
