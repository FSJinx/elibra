<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateCirculationRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && ($user->isLibrarian() || $user->isAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'borrowed_at' => ['sometimes', 'date'],
            'due_at' => ['sometimes', 'date', 'after_or_equal:borrowed_at'],
            'returned_at' => ['sometimes', 'nullable', 'date', 'after_or_equal:borrowed_at'],

            'status' => ['sometimes', Rule::in(['borrowed', 'returned', 'overdue', 'lost', 'cancelled'])],
            'renewal_count' => ['sometimes', 'integer', 'min:0'],
            'fine_charged' => ['sometimes', 'numeric', 'min:0'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:255'],

            'processed_by' => ['sometimes', 'integer', Rule::exists('users', 'id')],
            'accession_id' => ['sometimes', 'integer', Rule::exists('accessions', 'id')],
            'patron_id' => ['sometimes', 'integer', Rule::exists('users', 'id')],
            'loan_mode_id' => ['sometimes', 'integer', Rule::exists('loan_modes', 'id')],
            'return_received_by' => ['sometimes', 'nullable', 'integer', Rule::exists('users', 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'borrowed_at.date' => 'The borrowed date and time must be a valid date.',
            'due_at.date' => 'The due date and time must be a valid date.',
            'due_at.after_or_equal' => 'The due date must be on or after the borrowed date.',

            'returned_at.date' => 'The returned date and time must be a valid date.',
            'returned_at.after_or_equal' => 'The returned date must be on or after the borrowed date.',

            'status.in' => 'The status must be one of: borrowed, returned, overdue, lost, or cancelled.',

            'renewal_count.integer' => 'The renewal count must be a whole number.',
            'renewal_count.min' => 'The renewal count must be 0 or greater.',

            'fine_charged.numeric' => 'The fine charged must be a valid number.',
            'fine_charged.min' => 'The fine charged must not be negative.',

            'notes.string' => 'The notes must be a valid string.',
            'notes.max' => 'The notes may not exceed 255 characters.',

            'processed_by.integer' => 'The processed by user must be a valid ID.',
            'processed_by.exists' => 'The selected processed by user does not exist.',

            'accession_id.integer' => 'The accession ID must be a valid integer.',
            'accession_id.exists' => 'The selected accession does not exist.',

            'patron_id.integer' => 'The patron ID must be a valid integer.',
            'patron_id.exists' => 'The selected patron does not exist.',

            'loan_mode_id.integer' => 'The loan mode ID must be a valid integer.',
            'loan_mode_id.exists' => 'The selected loan mode does not exist.',

            'return_received_by.integer' => 'The return receiver ID must be a valid integer.',
            'return_received_by.exists' => 'The selected return receiver does not exist.',
        ];
    }
}
