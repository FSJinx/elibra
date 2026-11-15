<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class StoreInventoryLineRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isLibrarian() || $this->user()?->isAdmin() || $this->user()?->isSuperAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'inventory_id' => ['required', 'integer', 'exists:inventories,id'],
            'shelved' => ['required', 'boolean'],
            'section_id' => ['required_if:shelved,false', 'nullable', 'integer', 'exists:sections,id'],
        ];
    }
}
