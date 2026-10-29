<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateLoanModeRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && $user->can('update', $this->route('loanMode'));
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $loanMode = $this->route('loanMode');

        return [
            'slug' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('loan_modes', 'slug')->ignore($loanMode)],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:255'],

            'renewable' => ['sometimes', 'boolean'],
            'charges_fine' => ['sometimes', 'boolean'],
            'has_grace_period' => ['sometimes', 'boolean'],

            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
