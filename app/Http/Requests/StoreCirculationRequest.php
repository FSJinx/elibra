<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreCirculationRequest extends BaseRequest
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
            'borrowed_at' => ['required', 'date'],
            'due_at' => ['required', 'date', 'after_or_equal:borrowed_at'],
            'returned_at' => ['nullable', 'date', 'after_or_equal:borrowed_at'],

            'status' => ['required', Rule::in(['borrowed', 'returned', 'overdue', 'lost', 'cancelled'])],
            'renewal_count' => ['nullable', 'integer', 'min:0'],
            'fine_charged' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:255'],

            'processed_by' => ['required', 'integer', Rule::exists('users', 'id')],
            'accession_id' => ['required', 'integer', Rule::exists('accessions', 'id')],
            'patron_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'loan_mode_id' => ['required', 'integer', Rule::exists('loan_modes', 'id')],
            'return_received_by' => ['nullable', 'integer', Rule::exists('users', 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'borrowed_at.required' => 'The borrowed date and time is required.',
            'borrowed_at.date' => 'The borrowed date and time must be a valid date.',

            'due_at.required' => 'The due date and time is required.',
            'due_at.date' => 'The due date and time must be a valid date.',
            'due_at.after_or_equal' => 'The due date must be on or after the borrowed date.',

            'returned_at.date' => 'The returned date and time must be a valid date.',
            'returned_at.after_or_equal' => 'The returned date must be on or after the borrowed date.',

            'status.required' => 'The circulation status is required.',
            'status.in' => 'The status must be one of: borrowed, returned, overdue, lost, or cancelled.',

            'renewal_count.integer' => 'The renewal count must be a whole number.',
            'renewal_count.min' => 'The renewal count must be 0 or greater.',

            'fine_charged.numeric' => 'The fine charged must be a valid number.',
            'fine_charged.min' => 'The fine charged must not be negative.',

            'notes.string' => 'The notes must be a valid string.',
            'notes.max' => 'The notes may not exceed 255 characters.',

            'processed_by.required' => 'The processed by user is required.',
            'processed_by.integer' => 'The processed by user must be a valid ID.',
            'processed_by.exists' => 'The selected processed by user does not exist.',

            'accession_id.required' => 'The accession is required.',
            'accession_id.integer' => 'The accession ID must be a valid integer.',
            'accession_id.exists' => 'The selected accession does not exist.',

            'patron_id.required' => 'The patron is required.',
            'patron_id.integer' => 'The patron ID must be a valid integer.',
            'patron_id.exists' => 'The selected patron does not exist.',

            'loan_mode_id.required' => 'The loan mode is required.',
            'loan_mode_id.integer' => 'The loan mode ID must be a valid integer.',
            'loan_mode_id.exists' => 'The selected loan mode does not exist.',

            'return_received_by.integer' => 'The return receiver ID must be a valid integer.',
            'return_received_by.exists' => 'The selected return receiver does not exist.',
        ];
    }
}
