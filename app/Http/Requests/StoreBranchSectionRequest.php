<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator as ValidationValidator;
use App\Models\Branch;
use App\Models\Librarian;

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

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'data' => $validator->errors(),
            ], 422)
        );
    }

    public function withValidator(ValidationValidator $validator): void
    {
        $validator->after(function (ValidationValidator $validator) {

            // No section head selected, nothing to validate.
            if (! $this->filled('section_head_id')) {
                return;
            }

            // branch_id is required, so we can safely retrieve it.
            $branch = Branch::find($this->branch_id);

            $librarian = Librarian::find($this->section_head_id);

            if (
                $branch &&
                $librarian &&
                $branch->campus_id !== $librarian->branch->campus_id
            ) {
                $validator->errors()->add(
                    'section_head_id',
                    'The selected section head must belong to the same campus as the branch.'
                );
            }
        });
    }
}
