<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && !auth()->user()->profile;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:lecturer,student',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'faculty' => 'required|string|max:255',
            'student_id' => 'nullable|string|max:255|unique:profiles,student_id',
            'orcid' => 'nullable|string|regex:/^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}$/',
            'bio' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Profile type is required.',
            'type.in' => 'Profile type must be either lecturer or student.',
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'department.required' => 'Department is required.',
            'faculty.required' => 'Faculty is required.',
            'student_id.unique' => 'This student/staff ID is already registered.',
            'orcid.regex' => 'ORCID must be in format: 0000-0000-0000-0000.',
            'bio.max' => 'Bio cannot exceed 1000 characters.',
            'website.url' => 'Website must be a valid URL.',
        ];
    }
}