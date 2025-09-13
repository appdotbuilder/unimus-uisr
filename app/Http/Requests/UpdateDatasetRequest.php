<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDatasetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $dataset = $this->route('dataset');
        return auth()->check() && 
               (auth()->id() === $dataset->user_id || auth()->user()->isAdmin()) &&
               $dataset->status !== 'published';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'domain' => 'required|string|max:255',
            'task' => 'required|string|max:255',
            'license' => 'required|string|max:255',
            'doi' => 'nullable|string|max:255|regex:/^10\.\d{4,}\/\S+$/',
            'access_level' => 'required|in:public,restricted,private',
            'collaboration_type' => 'required|in:local,national,international',
            'keywords' => 'nullable|array|max:10',
            'keywords.*' => 'string|max:50',
            'contributors' => 'nullable|array|max:10',
            'contributors.*' => 'string|max:255',
            'version' => 'nullable|string|max:10',
            'dataset_file' => 'nullable|file|mimes:csv,json|max:51200', // 50MB
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
            'title.required' => 'Dataset title is required.',
            'description.required' => 'Dataset description is required.',
            'description.min' => 'Dataset description must be at least 50 characters.',
            'domain.required' => 'Research domain is required.',
            'task.required' => 'Research task is required.',
            'license.required' => 'License information is required.',
            'doi.regex' => 'DOI must be in valid format (e.g., 10.1000/123).',
            'access_level.required' => 'Access level is required.',
            'access_level.in' => 'Access level must be public, restricted, or private.',
            'collaboration_type.required' => 'Collaboration type is required.',
            'collaboration_type.in' => 'Collaboration type must be local, national, or international.',
            'keywords.max' => 'Maximum 10 keywords allowed.',
            'contributors.max' => 'Maximum 10 contributors allowed.',
            'dataset_file.mimes' => 'Dataset file must be CSV or JSON format.',
            'dataset_file.max' => 'Dataset file size cannot exceed 50MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert keywords string to array if needed
        if (is_string($this->keywords)) {
            $this->merge([
                'keywords' => array_filter(array_map('trim', explode(',', $this->keywords))),
            ]);
        }

        // Convert contributors string to array if needed
        if (is_string($this->contributors)) {
            $this->merge([
                'contributors' => array_filter(array_map('trim', explode(',', $this->contributors))),
            ]);
        }
    }
}