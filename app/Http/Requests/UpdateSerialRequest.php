<?php

namespace App\Http\Requests;

use App\Models\Academic;
use App\Models\Branch;
use App\Models\Item;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateSerialRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('serial'));
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
            'title' => [ 'sometimes', 'sometimes', 'required', 'string', 'max:255' ],
            'subtitle' => [ 'sometimes', 'sometimes', 'nullable', 'string', 'max:255' ],
            'description' => [ 'sometimes', 'nullable', 'string' ],
            'call_number' => [ 'sometimes', 'nullable', 'string', 'max:255', Rule::unique((new Academic)->getTable(), 'call_number') ],
            'language' => [ 'sometimes', 'required', 'string', 'max:255' ],
            'publication_year' => [ 'sometimes', 'nullable', 'integer', 'min:1900', 'max:' . date('Y') ],
            'keywords' => [ 'sometimes', 'nullable', 'string' ],
            'electronic_file' => [ 'sometimes', 'nullable', 'file', 'mimes:pdf,doc,docx' ],
            'branch_id' => [ 'sometimes', 'required', Rule::exists((new Branch)->getTable(), 'id') ],

            //Serial Fields
            'isbn_issn' => [ 'sometimes', 'nullable', 'string', 'max:255' ],
            'volume' => [ 'sometimes', 'nullable', 'string', 'max:255' ],
            'issue' => ['sometimes',  'nullable', 'string', 'max:255' ],
            'pages' => [ 'sometimes', 'nullable', 'string', 'max:255' ],
            'doi' => [ 'sometimes', 'nullable', 'string', 'max:255' ],
            'item_id' => [ 'sometimes', 'required', Rule::exist((new Item)->getTable(), 'id') ], 
        ];

        return $rules;

    }
}
