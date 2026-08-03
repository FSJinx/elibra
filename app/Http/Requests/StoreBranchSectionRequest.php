<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator as ValidationValidator;
use App\Models\Branch;
use App\Models\BranchSection;
use App\Models\Librarian;
use App\Models\Sections;
use Illuminate\Validation\Rule;

class StoreBranchSectionRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', [BranchSection::class, Branch::find($this->branch_id)]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'branch_id' => [ 'required', Rule::exists((new Branch)->getTable(), 'id') ],
            'section_id' => [
                'required',
                Rule::exists((new Sections)->getTable(), 'id'),

                Rule::unique((new BranchSection)->getTable(), 'section_id')
                    ->where(fn ($query) => $query->where('branch_id', $this->branch_id)),
            ],
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
            'section_id.unique' => 'This section is already assigned to the selected branch.',
        ];

    }


    // public function withValidator(ValidationValidator $validator): void
    // {
    //     $validator->after(function (ValidationValidator $validator) {

    //         // No section head selected, nothing to validate.
    //         if (! $this->filled('section_head_id')) {
    //             return;
    //         }

    //         // branch_id is required, so we can safely retrieve it.
    //         $branch = Branch::find($this->branch_id);

    //         $librarian = Librarian::find($this->section_head_id);

    //         if (
    //             $branch &&
    //             $librarian &&
    //             $branch->campus_id !== $librarian->branch->campus_id
    //         ) {
    //             $validator->errors()->add(
    //                 'section_head_id',
    //                 'The selected section head must belong to the same campus as the branch.'
    //             );
    //         }
    //     });
    // }
}
