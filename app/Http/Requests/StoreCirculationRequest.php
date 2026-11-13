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
            'patron' => ['required', 'array'],
            'patron.id' => ['required', 'integer', Rule::exists('patrons', 'id')],
            'loan_mode_id' => ['required', 'integer', Rule::exists('loan_modes', 'id')],

            'accessions' => ['required', 'array', 'min:1'],
            'accessions.*' => ['required', 'array'],
            'accessions.*.id' => ['required', 'integer', 'distinct', Rule::exists('accessions', 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'accessions.required' => 'At least one accession is required.',
            'accessions.min' => 'At least one accession is required.',
            'accessions.*.id.required' => 'The accession is required.',
            'accessions.*.id.integer' => 'The accession ID must be a valid integer.',
            'accessions.*.id.exists' => 'The selected accession does not exist.',

            'patron.id.required' => 'The patron is required.',
            'patron.id.integer' => 'The patron ID must be a valid integer.',
            'patron.id.exists' => 'The selected patron profile does not exist.',

            'loan_mode_id.required' => 'The loan mode is required.',
            'loan_mode_id.integer' => 'The loan mode ID must be a valid integer.',
            'loan_mode_id.exists' => 'The selected loan mode does not exist.',
        ];
    }
}
