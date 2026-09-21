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
            'request_id' => [ 'sometimes', 'uuid', 'unique:acquisition_requests,request_id' ],
            'requested_by' => [ 'required', 'integer', Rule::exists('users', 'id') ],
            'item_type_id' => [ 'nullable', 'integer', Rule::exists((new ItemType)->getTable(), 'id') ],
            'reviewed_by' => [ 'nullable', 'integer', Rule::exists('users', 'id') ],

            'title' => [ 'required', 'string', 'max:255' ],
            'author' => [ 'nullable', 'string', 'max:255' ],
            'isbn' => [ 'nullable', 'string', 'max:20' ],
            'publisher' => [ 'nullable', 'string', 'max:255' ],
            'publication_year' => [ 'nullable', 'integer', 'min:1900', 'max:2100' ],
            'edition' => [ 'nullable', 'string', 'max:255' ],

            'subject' => [ 'nullable', 'string', 'max:255' ],
            'quantity' => [ 'required', 'integer', 'min:1' ],
            'justification' => [ 'nullable', 'string' ],
            'priority' => [ 'nullable', Rule::in(['low', 'normal', 'high', 'urgent']) ],

            'estimated_unit_price' => [ 'nullable', 'numeric', 'min:0' ],
            'estimated_total_price' => [ 'nullable', 'numeric', 'min:0' ],
            'preferred_supplier' => [ 'nullable', 'string', 'max:255' ],

            'request_status' => [ 'nullable', Rule::in(['pending', 'approved', 'rejected']) ],
            'procurement_status' => [ 'nullable', Rule::in(['pending', 'ordered', 'received']) ],
            'is_closed' => [
                'nullable',
                'boolean',
                Rule::prohibitedIf(in_array($this->input('procurement_status'), ['ordered', 'received'], true)),
            ],
            'closed_remarks' => [ 'nullable', 'string' ],
            'closed_descriptions' => [ 'nullable', 'string' ],
            'closed_description' => [ 'nullable', 'string' ],
            'reviewed_at' => [ 'nullable', 'date' ],
            'remarks' => [ 'nullable', 'string' ],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->user()) {
            $this->merge([
                'requested_by' => $this->user()->id,
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'request_id.uuid' => 'The request ID must be a valid UUID.',
            'request_id.unique' => 'This request ID has already been used.',

            'requested_by.required' => 'The requester is required.',
            'requested_by.integer' => 'The requester ID must be a valid integer.',
            'requested_by.exists' => 'The selected requester does not exist.',

            'item_type_id.integer' => 'The item type ID must be a valid integer.',
            'item_type_id.exists' => 'The selected item type does not exist.',

            'reviewed_by.integer' => 'The reviewer ID must be a valid integer.',
            'reviewed_by.exists' => 'The selected reviewer does not exist.',

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

            'estimated_total_price.numeric' => 'The estimated total price must be a number.',
            'estimated_total_price.min' => 'The estimated total price must not be negative.',

            'preferred_supplier.string' => 'The preferred supplier must be a string.',
            'preferred_supplier.max' => 'The preferred supplier may not be greater than 255 characters.',

            'request_status.in' => 'The request status must be pending, approved, or rejected.',
            'procurement_status.in' => 'The procurement status must be pending, ordered, or received.',
            'is_closed.boolean' => 'The closed flag must be true or false.',
            'is_closed.prohibited' => 'The closed flag is not editable when procurement status is already ordered or received.',
            'closed_remarks.string' => 'The closed remarks must be a string.',
            'closed_descriptions.string' => 'The system generated closure description must be a string.',
            'closed_description.string' => 'The closure description must be a string.',
            'reviewed_at.date' => 'The reviewed date must be a valid date.',
            'remarks.string' => 'The remarks must be a string.',
        ];
    }
}