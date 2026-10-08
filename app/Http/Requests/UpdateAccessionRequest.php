<?php

namespace App\Http\Requests;

use App\Models\AcquisitionLines;
use App\Models\Item;
use App\Models\Sections;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateAccessionRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermission('accession.update') && ($this->user()->isLibrarian() && $this->user()->librarian->branch)
            || $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
                'accession_number' => [ 'sometimes', 'nullable', 'string', 'max:255', 'unique:accessions,accession_number,' . $this->route('accession')->id ],
                'status' => [ 'sometimes', 'required', Rule::in([ 'available', 'reserved', 'on_load', 'lost', 'missing', 'archived', 'condemned' ]), ],
                'remarks' => [ 'sometimes', 'nullable', 'string', 'max:255' ],  

                'item_id' => [ 'sometimes', 'required', 'integer', Rule::exists((new Item)->getTable(), 'id')],
                'section_id' => [ 'sometimes', 'required', 'integer', Rule::exists((new Sections)->getTable(), 'id')],
                'acquisition_line_id' => [ 'sometimes', 'required', 'integer', Rule::exists((new AcquisitionLines)->getTable(), 'id')],
        ];

        return $rules;
    }
}
