<?php

namespace App\Http\Requests;

use App\Models\Academic;
use App\Models\Branch;
use App\Models\Item;
use App\Models\Serial;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreSerialRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Serial::class);
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
            'call_number' => [ 'nullable', 'string', 'max:255', Rule::unique((new Academic)->getTable(), 'call_number') ],
            'language' => [ 'required', 'string', 'max:255' ],
            'publication_year' => [ 'nullable', 'integer', 'min:1900', 'max:' . date('Y') ],
            'keywords' => [ 'nullable', 'string' ],
            'electronic_file' => [ 'nullable', 'file', 'mimes:pdf,doc,docx' ],
            'branch_id' => [ 'required', Rule::exists((new Branch)->getTable(), 'id') ],

            //Serial Fields
            'isbn_issn' => [ 'nullable', 'string', 'max:255' ],
            'volume' => [ 'nullable', 'string', 'max:255' ],
            'issue' => [ 'nullable', 'string', 'max:255' ],
            'pages' => [ 'nullable', 'string', 'max:255' ],
            'doi' => [ 'nullable', 'string', 'max:255' ],
            'item_id' => [ 'required', Rule::exist((new Item)->getTable(), 'id') ], 

        ];

        return $rules;
    }
}
