<?php

namespace App\Http\Requests;

use App\Models\LoanMode;
use App\Models\PatronType;
use App\Models\PatronTypeLoanPolicy;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StorePatronTypeLoanPolicyRequest extends BaseRequest
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
            'name' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255', Rule::unique(PatronTypeLoanPolicy::class, 'key')],
            'description' => ['nullable', 'string', 'max:255'],

            'can_reserve' => ['boolean'],
            'reservation_limit' => ['required', 'integer', 'min:0'],
            'loan_period_days' => ['required', 'integer', 'min:1'],

            'max_items' => ['required', 'integer', 'min:1'],
            'max_renewals' => ['required', 'integer', 'min:0'],
            'fine_per_due' => ['required', 'numeric', 'min:0'],
            'grace_period' => ['required', 'integer', 'min:0'],

            'notes' => ['nullable', 'string', 'max:255'],

            'patron_type_id' => ['required', 'integer', Rule::exists((new PatronType)->getTable(), 'id')],
            'loan_mode_id' => ['required', 'integer', Rule::exists((new LoanMode)->getTable(), 'id')]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The policy name is required.',
            'name.string' => 'The policy name must be a valid string.',
            'name.max' => 'The policy name may not exceed 255 characters.',

            'key.required' => 'The policy key is required.',
            'key.string' => 'The policy key must be a valid string.',
            'key.max' => 'The policy key may not exceed 255 characters.',
            'key.unique' => 'This policy key already exists.',

            'description.string' => 'The description must be a valid string.',
            'description.max' => 'The description may not exceed 255 characters.',

            'can_reserve.boolean' => 'The reserve option must be true or false.',

            'reservation_limit.required' => 'The reservation limit is required.',
            'reservation_limit.integer' => 'The reservation limit must be a whole number.',
            'reservation_limit.min' => 'The reservation limit must be 0 or greater.',

            'loan_period_days.required' => 'The loan period is required.',
            'loan_period_days.integer' => 'The loan period must be a whole number.',
            'loan_period_days.min' => 'The loan period must be at least 1 day.',

            'max_items.required' => 'The maximum items field is required.',
            'max_items.integer' => 'The maximum items must be a whole number.',
            'max_items.min' => 'The maximum items must be at least 1.',

            'max_renewals.required' => 'The maximum renewals field is required.',
            'max_renewals.integer' => 'The maximum renewals must be a whole number.',
            'max_renewals.min' => 'The maximum renewals must be 0 or greater.',

            'fine_per_due.required' => 'The fine per due is required.',
            'fine_per_due.numeric' => 'The fine per due must be a valid number.',
            'fine_per_due.min' => 'The fine per due must be 0 or greater.',

            'grace_period.required' => 'The grace period is required.',
            'grace_period.integer' => 'The grace period must be a whole number.',
            'grace_period.min' => 'The grace period must be 0 or greater.',

            'notes.string' => 'The notes must be a valid string.',
            'notes.max' => 'The notes may not exceed 255 characters.',

            'patron_type_id.required' => 'The patron type is required.',
            'patron_type_id.integer' => 'The patron type must be a valid ID.',
            'patron_type_id.exists' => 'The selected patron type does not exist.',

            'loan_mode_id.required' => 'The loan mode is required.',
            'loan_mode_id.integer' => 'The loan mode must be a valid ID.',
            'loan_mode_id.exists' => 'The selected loan mode does not exist.',
        ];
    }
}
