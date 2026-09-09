<?php

namespace App\Http\Requests;

use App\Models\ItemType;
use Illuminate\Validation\Rule;

class StoreAcquisitionRequestRequest extends BaseRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && ($user->isLibrarian() || $user->isAdmin());
    }

    public function rules(): array
    {
        return [
            'item_type_id' => ['nullable','integer', Rule::exists((new ItemType)->getTable(), 'id'),],

            'title' => [ 'required','string','max:255',],

            'author' => [ 'nullable', 'string', 'max:255' ],

            'isbn' => [ 'nullable', 'string', 'max:20' ],

            'publisher' => [ 'nullable', 'string', 'max:255' ],

            'publication_year' => [ 'nullable', 'integer', 'min:1900', 'max:2100' ],

            'edition' => [ 'nullable', 'string', 'max:255' ],

            'subject' => [ 'nullable', 'string', 'max:255' ],

            'quantity' => [ 'required', 'integer', 'min:1' ],

            'justification' => [ 'nullable', 'string' ],

            'priority' => [ 'nullable',
                Rule::in(['low', 'normal', 'high', 'urgent',]),],

            'estimated_unit_price' => [ 'nullable', 'numeric', 'min:0'  ],

            'preferred_supplier' => [ 'nullable', 'string', 'max:255',],

            'remarks' => [ 'nullable', 'string' ],
        ];
    }

    public function messages(): array
    {
        return [
            'item_type_id.integer' => 'The item type ID must be a valid integer.',
            'item_type_id.exists' => 'The selected item type does not exist.',

            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',

            'author.string' => 'The author must be a string.',
            'author.max' => 'The author may not be greater than 255 characters.',

            'isbn.string' => 'The ISBN must be a string.',
            'isbn.max' => 'The ISBN may not be greater than 20 characters.',

            'publisher.string' => 'The publisher must be a string.',
            'publisher.max' => 'The publisher may not be greater than 255 characters.',

            'publication_year.integer' => 'The publication year must be a valid integer.',
            'publication_year.min' => 'The publication year must not be earlier than 1900.',
            'publication_year.max' => 'The publication year must not be later than 2100.',

            'edition.string' => 'The edition must be a string.',
            'edition.max' => 'The edition may not be greater than 255 characters.',

            'subject.string' => 'The subject must be a string.',
            'subject.max' => 'The subject may not be greater than 255 characters.',

            'quantity.required' => 'The quantity field is required.',
            'quantity.integer' => 'The quantity must be a valid integer.',
            'quantity.min' => 'The quantity must be at least 1.',

            'justification.string' => 'The justification must be a string.',

            'priority.in' => 'The priority must be low, normal, high, or urgent.',

            'estimated_unit_price.numeric' => 'The estimated unit price must be a number.',
            'estimated_unit_price.min' => 'The estimated unit price must not be negative.',

            'preferred_supplier.string' => 'The preferred supplier must be a string.',
            'preferred_supplier.max' => 'The preferred supplier may not be greater than 255 characters.',

            'remarks.string' => 'The remarks must be a string.',
        ];
    }
}