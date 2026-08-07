<?php

namespace App\Http\Requests;

use App\Models\Academic;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Item;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreAcademicRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Academic::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            //Item Fields
            'title' => [ 'required', 'string', 'max:255' ],
            'subtitle' => [ 'nullable', 'string', 'max:255' ],
            'description' => [ 'nullable', 'string' ],
            'call_number' => [ 'nullable', 'string', 'max:255', Rule::unique((new Item)->getTable(), 'call_number') ],
            'language' => [ 'required', 'string', 'max:255' ],
            'publication_year' => [ 'nullable', 'integer', 'min:1900', 'max:' . date('Y') ],
            'keywords' => [ 'nullable', 'string' ],
            'electronic_file' => [ 'nullable', 'file', 'mimes:pdf,doc,docx' ],
            'branch_id' => [ 'required', Rule::exists((new Branch)->getTable(), 'id') ],

            //Acdemic Fields
            'category' => [ 'required', Rule::in(['undergraduate thesis', 'graduate thesis', 'case study', 'research paper', 'feasibility study']) ],
            'subjects' => [ 'nullable', 'array' ],
            'subjects.*' => [ 'string', 'max:255' ],
            'doi' => [ 'nullable', 'string', 'max:255' ],
            // 'item_id' => [ 'required', Rule::exists((new Item)->getTable(), 'id') ],
            'department_id' => [ 'required', Rule::exists((new Department)->getTable(), 'id') ],

        ];
        return $rules;
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
            'item_id.required' => 'Item is required',
            'item_id.exists' => 'Selected item does not exist',
            'department_id.required' => 'Department is required',
            'department_id.exists' => 'Selected department does not exist',
        ];
    }
}
