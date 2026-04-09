<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVacancyRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array

    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'type' => 'required|in:Full-time,Remote,Hybrid,Part-time',
            'category_id' => 'required|exists:job_categories,id',
            'company_id' => 'required|exists:companies,id',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'The job title is required.',
            'description.required' => 'The job description is required.',
            'location.required' => 'The job location is required.',
            'salary.required' => 'The salary is required.',
            'type.required' => 'The job type is required.',
            'category_id.required' => 'The job category is required.',
            'company_id.required' => 'The company is required.',
        ];
    }
}
