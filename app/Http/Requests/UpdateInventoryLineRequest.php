<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class UpdateInventoryLineRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('inventoryLine'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'new_status' => 'required',
            'new_section_id' => 'sometimes|nullable|exists:sections,id',
            'verifier_id' => 'sometimes|nullable|exists:librarians,id',
        ];
    }
}
