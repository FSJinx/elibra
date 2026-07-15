<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicRequest extends BaseRequest
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
        return [
            // Item table fields
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',

            // Academic table fields
            'call_number' => 'required|string|max:255|unique:academics,call_number',
            'language' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'subjects' => 'nullable|array',
            'subjects.*' => 'string|max:255',
            'department_id' => 'required|exists:departments,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'branch_id.required' => 'Branch is required',
            'branch_id.exists' => 'Selected branch does not exist',
            'call_number.required' => 'Call number is required',
            'call_number.unique' => 'Call number already exists',
            'language.required' => 'Language is required',
            'category.required' => 'Category is required',
            'department_id.required' => 'Department is required',
            'department_id.exists' => 'Selected department does not exist',
        ];
    }
}
