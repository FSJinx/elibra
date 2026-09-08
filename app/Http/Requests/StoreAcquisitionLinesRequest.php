<?php

namespace App\Http\Requests;

use App\Models\Acquisition;
use App\Models\Item;
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
            'quantity' => [ 'required', 'integer', 'min:1', ],
            'unit_price' => [ 'required', 'integer', 'min:0', ],
            'discount' => [ 'required', 'integer', 'min:0', ],
            'net_price' => [ 'required', 'integer', 'min:0', ],

            'item_id' => [ 'required', Rule::exists((new Item)->getTable(), 'id')],
            'acquisition_id' => [ 'required', Rule::exists((new Acquisition)->getTable(), 'id')],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be a whole number.',
            'quantity.min' => 'Quantity must be at least 1.',

            'unit_price.required' => 'Unit price is required.',
            'unit_price.numeric' => 'Unit price must be a valid number.',
            'unit_price.min' => 'Unit price cannot be negative.',

            'discount.required' => 'Discount is required.',
            'discount.numeric' => 'Discount must be a valid number.',
            'discount.min' => 'Discount cannot be negative.',

            'net_price.required' => 'Net price is required.',
            'net_price.numeric' => 'Net price must be a valid number.',
            'net_price.min' => 'Net price cannot be negative.',

            'item_id.required' => 'Item is required.',
            'item_id.integer' => 'Item ID must be a valid number.',
            'item_id.exists' => 'The selected item does not exist.',

            'acquisition_id.required' => 'Acquisition is required.',
            'acquisition_id.integer' => 'Acquisition ID must be a valid number.',
            'acquisition_id.exists' => 'The selected acquisition does not exist.',
        ];
    }
}
