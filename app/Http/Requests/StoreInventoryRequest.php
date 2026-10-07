<?php

namespace App\Http\Requests;

use App\Models\Librarian;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreInventoryRequest extends BaseRequest
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
            'inventory_code' => ['required', 'string', 'max:255', 'unique:inventories,inventory_code'],
            'is_completed' => ['required', 'boolean'],
            'librarian_id' => ['required', 'integer', Rule::exists((new Librarian)->getTable(), 'id')],
        ];
    }
}
