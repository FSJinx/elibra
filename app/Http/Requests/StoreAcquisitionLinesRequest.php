<?php

namespace App\Http\Requests;

use App\Models\Acquisition;
use App\Models\Item;
use App\Models\Sections;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreAcquisitionLinesRequest extends BaseRequest
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
            'quantity' => [ 'required', 'numeric', 'min:1', ],
            'unit_price' => [ 'nullable', 'numeric', 'min:0', ],
            'discount' => [ 'nullable', 'numeric', 'min:0', ],
            'net_price' => [ 'nullable', 'numeric', 'min:0', ],

            'item_id' => [ 'required', Rule::exists((new Item)->getTable(), 'id')],
            'acquisition_id' => [ 'required', Rule::exists((new Acquisition)->getTable(), 'id')],
            'section_id' => [ 'nullable', Rule::exists((new Sections)->getTable(), 'id')],

        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'quantity.numeric' => 'Quantity must be a valid number.',
            'quantity.min' => 'Quantity must be at least 1.',

            'unit_price.numeric' => 'Unit price must be a valid number.',
            'unit_price.min' => 'Unit price cannot be negative.',

            'discount.numeric' => 'Discount must be a valid number.',
            'discount.min' => 'Discount cannot be negative.',

            'net_price.numeric' => 'Net price must be a valid number.',
            'net_price.min' => 'Net price cannot be negative.',

            'item_id.required' => 'Item is required.',
            'item_id.integer' => 'Item ID must be a valid number.',
            'item_id.exists' => 'The selected item does not exist.',

            'acquisition_id.required' => 'Acquisition is required.',
            'acquisition_id.integer' => 'Acquisition ID must be a valid number.',
            'acquisition_id.exists' => 'The selected acquisition does not exist.',

            // 'section_id.required' => 'Section is required.',
            'section_id.integer' => 'Section ID must be a valid number.',
            'section_id.exists' => 'The selected section does not exist.',
        ];
    }
}
