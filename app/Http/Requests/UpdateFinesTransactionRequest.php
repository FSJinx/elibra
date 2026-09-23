<?php

namespace App\Http\Requests;

use App\Models\Circulation;
use App\Models\Patron;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateFinesTransactionRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => ['sometimes', 'required', 'numeric', 'min:0'],
            'transaction_type' => ['sometimes', 'required', Rule::in(['charged', 'paid', 'waived'])],
            'remarks' => ['sometimes', 'nullable', 'string'],
            'patron_id' => ['sometimes', 'required', 'integer', Rule::exists((new Patron)->getTable(), 'id')],
            'circulation_id' => ['sometimes', 'required', 'integer', Rule::exists((new Circulation)->getTable(), 'id')],
            'processed_by' => ['sometimes', 'required', 'integer', Rule::exists((new User)->getTable(), 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'The fine amount is required.',
            'amount.numeric' => 'The fine amount must be a valid number.',
            'amount.min' => 'The fine amount must be 0 or greater.',
            'transaction_type.required' => 'The transaction type is required.',
            'transaction_type.in' => 'The transaction type must be one of: charged, paid, waived.',
            'remarks.string' => 'Remarks must be a valid string.',
            'patron_id.required' => 'The patron is required.',
            'patron_id.exists' => 'The selected patron does not exist.',
            'circulation_id.required' => 'The circulation record is required.',
            'circulation_id.exists' => 'The selected circulation record does not exist.',
            'processed_by.required' => 'The processor is required.',
            'processed_by.exists' => 'The selected processor does not exist.',
        ];
    }
}
