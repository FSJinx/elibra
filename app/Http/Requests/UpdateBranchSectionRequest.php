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

class UpdateBranchSectionRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', [ $this->route('branchSection'), Branch::find($this->branch_id) ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'branch_id' => [
                'sometimes',
                Rule::exists((new Branch)->getTable(), 'id'),
            ],

            'section_id' => [
                'sometimes',
                Rule::exists((new Sections)->getTable(), 'id'),

                Rule::unique((new BranchSection)->getTable(), 'section_id')
                    ->where(fn ($query) => $query->where('branch_id', $this->branch_id))
                    ->ignore($this->route('branch_section')),
            ],
        ];
    }


    // public function withValidator(ValidationValidator $validator): void
    // {
    //     $validator->after(function (ValidationValidator $validator) {

    //         if (! $this->filled('section_head_id')) {
    //             return;
    //         }

    //         $branchSection = $this->route('branchSection');

    //         $branch = $this->filled('branch_id')
    //             ? Branch::find($this->branch_id)
    //             : $branchSection->branch;

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
