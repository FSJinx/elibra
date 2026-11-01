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

        return $user && ($user->isLibrarian() || $user->isAdmin() || $user->isSuperAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'accession_id' => ['required', 'integer', Rule::exists('accessions', 'id')],
            'patron_id' => ['required', 'integer', Rule::exists('patrons', 'id')],
            'loan_mode_id' => ['required', 'integer', Rule::exists('loan_modes', 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'accession_id.required' => 'The accession is required.',
            'accession_id.integer' => 'The accession ID must be a valid integer.',
            'accession_id.exists' => 'The selected accession does not exist.',

            'patron_id.required' => 'The patron is required.',
            'patron_id.integer' => 'The patron ID must be a valid integer.',
            'patron_id.exists' => 'The selected patron profile does not exist.',

            'loan_mode_id.required' => 'The loan mode is required.',
            'loan_mode_id.integer' => 'The loan mode ID must be a valid integer.',
            'loan_mode_id.exists' => 'The selected loan mode does not exist.',
        ];
    }
}
