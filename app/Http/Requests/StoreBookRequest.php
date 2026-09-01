<?php

namespace App\Http\Requests;

use App\Models\Author;
use App\Models\Book;
use App\Models\Branch;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\ItemTypeCategory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreBookRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Book::class);
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
            'item_type_id' => [ 'required', Rule::exists((new ItemType)->getTable(), 'id') ],
            'item_type_category_id' => [ 'required', Rule::exists((new ItemTypeCategory)->getTable(), 'id') ],
            'branch_id' => [ 'required', Rule::exists((new Branch())->getTable(), 'id') ],

            //Book Fields
            'edition' => [ 'required', 'string', 'max:255' ],
            'isbn_issn' => [ 'required', 'string', 'max:255' ],
            'copyright_year' => [ 'required', 'string', 'max:255' ],

            //Authors Field
            'author_ids' => [ 'nullable', 'array', 'min:1' ],
            'author_ids.*' => [ 'integer', Rule::exists((new Author)->getTable(), 'id')->whereNull('deleted_at') ],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            // Item Fields
            'title.required' => 'The title is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',

            'subtitle.string' => 'The subtitle must be a string.',
            'subtitle.max' => 'The subtitle may not be greater than 255 characters.',

            'description.string' => 'The description must be a string.',

            'call_number.string' => 'The call number must be a string.',
            'call_number.max' => 'The call number may not be greater than 255 characters.',
            'call_number.unique' => 'The call number has already been taken.',

            'language.required' => 'The language is required.',
            'language.string' => 'The language must be a string.',
            'language.max' => 'The language may not be greater than 255 characters.',

            'publication_year.integer' => 'The publication year must be a valid year.',
            'publication_year.min' => 'The publication year must not be earlier than 1900.',
            'publication_year.max' => 'The publication year cannot be greater than the current year.',

            'keywords.string' => 'The keywords must be a string.',

            'electronic_file.file' => 'The electronic file must be a valid file.',
            'electronic_file.mimes' => 'The electronic file must be a PDF, DOC, or DOCX file.',

            'item_type_id.required' => 'The item type is required.',
            'item_type_id.exists' => 'The selected item type is invalid.',

            'item_type_category_id.required' => 'The item type category is required.',
            'item_type_category_id.exists' => 'The selected item type category is invalid.',

            'branch_id.required' => 'The branch is required.',
            'branch_id.exists' => 'The selected branch is invalid.',

            // Book Fields
            'edition.required' => 'The edition is required.',
            'edition.string' => 'The edition must be a string.',
            'edition.max' => 'The edition may not be greater than 255 characters.',

            'isbn_issn.required' => 'The ISBN/ISSN is required.',
            'isbn_issn.string' => 'The ISBN/ISSN must be a string.',
            'isbn_issn.max' => 'The ISBN/ISSN may not be greater than 255 characters.',

            'copyright_year.required' => 'The copyright year is required.',
            'copyright_year.string' => 'The copyright year must be a string.',
            'copyright_year.max' => 'The copyright year may not be greater than 255 characters.',

            // Authors
            'author_ids.array' => 'The authors must be provided as an array.',
            'author_ids.min' => 'If authors are provided, at least one author must be selected.',

            'author_ids.*.integer' => 'Each author ID must be a valid integer.',
            'author_ids.*.exists' => 'One or more selected authors are invalid or have been deleted.',
        ];
    }

}
