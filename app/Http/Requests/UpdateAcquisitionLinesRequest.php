<?php

namespace App\Http\Requests;

use App\Models\Acquisition;
use App\Models\Item;
use App\Models\Sections;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateAcquisitionLinesRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return 
            $user->hasPermission('acquisition.line.create') && ($user->isLibrarian() && $user->librarian->branch)
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
            'quantity' => [ 'sometimes', 'required', 'numeric', 'min:1', ],
            'unit_price' => [ 'sometimes', 'nullable', 'numeric', 'min:0', ],
            'discount' => [ 'sometimes', 'nullable', 'numeric', 'min:0', ],
            'net_price' => [ 'sometimes', 'nullable', 'numeric', 'min:0', ],

            'item_id' => [ 'sometimes', 'required', Rule::exists((new Item)->getTable(), 'id')],
            'acquisition_id' => [ 'sometimes', 'required', Rule::exists((new Acquisition)->getTable(), 'id')],
            'section_id' => [ 'sometimes', 'nullable', Rule::exists((new Sections)->getTable(), 'id')],
        ];

        return $rules;
    }
}
