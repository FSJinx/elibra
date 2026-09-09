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
            // 'acquisition_line_id' => [ 'required', 'integer', Rule::exists((new AcquisitionLines)->getTable(), 'id')],

            // acquisition lines fields
            

        ];

        return $rules;
    }
}
