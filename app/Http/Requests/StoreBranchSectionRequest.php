<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBranchSectionRequest extends FormRequest
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
            'section_head_id' => 'nullable|exists:librarians,id',
            'branch_id' => 'required|exists:branches,id',
            'section_id' => 'required|exists:sections,id',
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
            'branch_id.required' => 'Please select a branch.',
            'branch_id.exists' => 'The selected branch is invalid.',

            'section_id.required' => 'Please select a section.',
            'section_id.exists' => 'The selected section is invalid.',

            'section_head_id.exists' => 'The selected section head is invalid.',
        ];

    }
}
