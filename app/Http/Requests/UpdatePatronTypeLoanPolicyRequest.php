<?php

namespace App\Http\Requests;

use App\Models\LoanMode;
use App\Models\PatronType;
use App\Models\PatronTypeLoanPolicy;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdatePatronTypeLoanPolicyRequest extends BaseRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'key' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique(PatronTypeLoanPolicy::class, 'key')->ignore($this->route('patronTypeLoanPolicy')?->id),
            ],
            'description' => ['sometimes', 'nullable', 'string', 'max:255'],

            'can_reserve' => ['sometimes', 'boolean'],
            'reservation_limit' => ['sometimes', 'integer', 'min:0'],
            'loan_period_days' => ['sometimes', 'integer', 'min:1'],

            'max_items' => ['sometimes', 'integer', 'min:1'],
            'max_renewals' => ['sometimes', 'integer', 'min:0'],
            'fine_per_due' => ['sometimes', 'numeric', 'min:0'],
            'grace_period' => ['sometimes', 'integer', 'min:0'],

            'notes' => ['sometimes', 'nullable', 'string', 'max:255'],

            'patron_type_id' => ['sometimes', 'integer', Rule::exists((new PatronType)->getTable(), 'id')],
            'loan_mode_id' => ['sometimes', 'integer', Rule::exists((new LoanMode)->getTable(), 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'The policy name must be a valid string.',
            'name.max' => 'The policy name may not exceed 255 characters.',

            'key.string' => 'The policy key must be a valid string.',
            'key.max' => 'The policy key may not exceed 255 characters.',
            'key.unique' => 'This policy key already exists.',

            'description.string' => 'The description must be a valid string.',
            'description.max' => 'The description may not exceed 255 characters.',

            'can_reserve.boolean' => 'The reserve option must be true or false.',

            'reservation_limit.integer' => 'The reservation limit must be a whole number.',
            'reservation_limit.min' => 'The reservation limit must be 0 or greater.',

            'loan_period_days.integer' => 'The loan period must be a whole number.',
            'loan_period_days.min' => 'The loan period must be at least 1 day.',

            'max_items.integer' => 'The maximum items must be a whole number.',
            'max_items.min' => 'The maximum items must be at least 1.',

            'max_renewals.integer' => 'The maximum renewals must be a whole number.',
            'max_renewals.min' => 'The maximum renewals must be 0 or greater.',

            'fine_per_due.numeric' => 'The fine per due must be a valid number.',
            'fine_per_due.min' => 'The fine per due must be 0 or greater.',

            'grace_period.integer' => 'The grace period must be a whole number.',
            'grace_period.min' => 'The grace period must be 0 or greater.',

            'notes.string' => 'The notes must be a valid string.',
            'notes.max' => 'The notes may not exceed 255 characters.',

            'patron_type_id.integer' => 'The patron type must be a valid ID.',
            'patron_type_id.exists' => 'The selected patron type does not exist.',

            'loan_mode_id.integer' => 'The loan mode must be a valid ID.',
            'loan_mode_id.exists' => 'The selected loan mode does not exist.',
        ];
    }
}
