<?php

namespace App\Http\Requests;

use App\Models\AcquisitionLines;
use App\Models\Item;
use App\Models\Sections;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreAccessionRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return 
        $user->hasPermission('accession.create') && ($user->isLibrarian() && $user->librarian->branch)
        || $user->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            // accession fields
            'accession_number' => [ 'nullable', 'string', 'max:255', 'unique:accessions,accession_number' ],
            'status' => [ 'required', Rule::in([ 'available', 'reserved', 'on_load', 'lost', 'missing', 'archived', 'condemned' ]), ],
            'remarks' => [  'nullable', 'string', 'max:255' ],

            // accession relationships
            'item_id' => [ 'required', 'integer', Rule::exists((new Item)->getTable(), 'id')],
            'section_id' => [ 'required', 'integer', Rule::exists((new Sections)->getTable(), 'id')],
            'acquisition_line_id' => [ 'required', 'integer', Rule::exists((new AcquisitionLines)->getTable(), 'id')],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'accession_number.string' => 'The accession number must be a valid text value.',
            'accession_number.max' => 'The accession number may not exceed 255 characters.',
            'accession_number.unique' => 'This accession number has already been used.',

            'status.required' => 'The status is required.',
            'status.in' => 'The selected status is invalid. Please choose available, reserved, on_load, lost, missing, archived, or condemned.',

            'remarks.string' => 'The remarks must be a valid text value.',
            'remarks.max' => 'The remarks may not exceed 255 characters.',

            'item_id.required' => 'The item ID is required.',
            'item_id.integer' => 'The item ID must be a valid integer.',
            'item_id.exists' => 'The selected item ID does not exist.',

            'section_id.required' => 'The section ID is required.',
            'section_id.integer' => 'The section ID must be a valid integer.',
            'section_id.exists' => 'The selected section ID does not exist.',

            'acquisition_line_id.required' => 'The acquisition line ID is required.',
            'acquisition_line_id.integer' => 'The acquisition line ID must be a valid integer.',
            'acquisition_line_id.exists' => 'The selected acquisition line ID does not exist.',
        ];
    }
}
