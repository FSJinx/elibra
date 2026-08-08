<?php

namespace App\Http\Requests;

use App\Models\Academic;
use App\Models\Branch;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\ItemTypeCategory;
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
            'item_type_id' => [ 'required', Rule::exists((new ItemType)->getTable(), 'id') ],
            'item_type_category_id' => [ 'required', Rule::exists((new ItemTypeCategory)->getTable(), 'id') ],
            'branch_id' => [ 'required', Rule::exists((new Branch)->getTable(), 'id') ],

            //Serial Fields
            'isbn_issn' => [ 'nullable', 'string', 'max:255' ],
            'volume' => [ 'nullable', 'string', 'max:255' ],
            'issue' => [ 'nullable', 'string', 'max:255' ],
            'pages' => [ 'nullable', 'string', 'max:255' ],
            'doi' => [ 'nullable', 'string', 'max:255' ],
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
            // Item Fields
            'title.required' => 'Title is required.',
            'title.string' => 'Title must be a valid string.',
            'title.max' => 'Title may not be greater than 255 characters.',

            'subtitle.string' => 'Subtitle must be a valid string.',
            'subtitle.max' => 'Subtitle may not be greater than 255 characters.',

            'description.string' => 'Description must be a valid string.',

            'call_number.string' => 'Call number must be a valid string.',
            'call_number.max' => 'Call number may not be greater than 255 characters.',
            'call_number.unique' => 'Call number already exists.',

            'language.required' => 'Language is required.',
            'language.string' => 'Language must be a valid string.',
            'language.max' => 'Language may not be greater than 255 characters.',

            'publication_year.integer' => 'Publication year must be a valid year.',
            'publication_year.min' => 'Publication year must be 1900 or later.',
            'publication_year.max' => 'Publication year cannot be greater than the current year.',

            'keywords.string' => 'Keywords must be a valid string.',

            'electronic_file.file' => 'Electronic file must be a valid file.',
            'electronic_file.mimes' => 'Electronic file must be a PDF, DOC, or DOCX file.',

            'item_type_id.required' => 'Item type is required.',
            'item_type_id.exists' => 'Selected item type does not exist.',

            'item_type_category_id.required' => 'Item type category is required.',
            'item_type_category_id.exists' => 'Selected item type category does not exist.',

            'branch_id.required' => 'Branch is required.',
            'branch_id.exists' => 'Selected branch does not exist.',

            // Serial Fields
            'isbn_issn.string' => 'ISBN/ISSN must be a valid string.',
            'isbn_issn.max' => 'ISBN/ISSN may not be greater than 255 characters.',

            'volume.string' => 'Volume must be a valid string.',
            'volume.max' => 'Volume may not be greater than 255 characters.',

            'issue.string' => 'Issue must be a valid string.',
            'issue.max' => 'Issue may not be greater than 255 characters.',

            'pages.string' => 'Pages must be a valid string.',
            'pages.max' => 'Pages may not be greater than 255 characters.',

            'doi.string' => 'DOI must be a valid string.',
            'doi.max' => 'DOI may not be greater than 255 characters.',
        ];
    }
}
