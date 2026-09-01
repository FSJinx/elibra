<?php

namespace App\Http\Requests;

use App\Models\Author;
use App\Models\Branch;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\ItemTypeCategory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('book'));
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
            'call_number' => [ 'sometimes', 'nullable', 'string', 'max:255', Rule::unique((new Item)->getTable(), 'call_number')->ignore($this->route('academic')) ],
            'language' => [ 'sometimes', 'required', 'string', 'max:255' ],
            'publication_year' => [ 'sometimes', 'nullable', 'integer', 'min:1900', 'max:' . date('Y') ],
            'keywords' => [ 'sometimes', 'nullable', 'string' ],
            'electronic_file' => [ 'sometimes', 'nullable', 'file', 'mimes:pdf,doc,docx' ],
            'item_type_id' => [ 'sometimes', 'required', Rule::exists((new ItemType)->getTable(), 'id') ],
            'item_type_category_id' => [ 'sometimes', 'required', Rule::exists((new ItemTypeCategory)->getTable(), 'id') ],
            'branch_id' => [ 'sometimes', 'required', Rule::exists((new Branch)->getTable(), 'id') ],

            //Academic Fields
            //Book Fields
            'edition' => [ 'sometimes', 'required', 'string', 'max:255' ],
            'isbn_issn' => [ 'sometimes', 'required', 'string', 'max:255' ],
            'copyright_year' => [ 'sometimes', 'required', 'string', 'max:255' ],

            //Authors Field
            'author_ids' => [ 'nullable', 'array', 'min:1' ],
            'author_ids.*' => [ 'integer', Rule::exists((new Author)->getTable(), 'id')->whereNull('deleted_at') ],
        ];
        return $rules;
    }
}
