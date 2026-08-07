<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Academic;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Item;
use Illuminate\Validation\Rule;

class UpdateAcademicRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('academic'));
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
            'title' => [ 'sometimes', 'required', 'string', 'max:255' ],
            'subtitle' => [ 'sometimes', 'nullable', 'string', 'max:255' ],
            'description' => [ 'sometimes', 'nullable', 'string' ],
            'call_number' => [ 'sometimes', 'nullable', 'string', 'max:255', Rule::unique((new Academic)->getTable(), 'call_number')->ignore($this->route('academic')) ],
            'language' => [ 'sometimes', 'required', 'string', 'max:255' ],
            'publication_year' => [ 'sometimes', 'nullable', 'integer', 'min:1900', 'max:' . date('Y') ],
            'keywords' => [ 'sometimes', 'nullable', 'string' ],
            'electronic_file' => [ 'sometimes', 'nullable', 'file', 'mimes:pdf,doc,docx' ],
            'branch_id' => [ 'sometimes', 'required', Rule::exists((new Branch)->getTable(), 'id') ],

            //Academic Fields
            'category' => [ 'sometimes', 'required', Rule::in(['undergraduate thesis', 'graduate thesis', 'case study', 'research paper', 'feasibility study']) ],
            'subjects' => [ 'sometimes', 'nullable', 'array' ],
            'subjects.*' => [ 'string', 'max:255' ],
            'doi' => [ 'sometimes', 'nullable', 'string', 'max:255' ],
            'item_id' => [ 'sometimes', Rule::exists((new Item)->getTable(), 'id') ],
            'department_id' => [ 'sometimes', 'required', Rule::exists((new Department)->getTable(), 'id') ],
        ];
        return $rules;
    }
}
