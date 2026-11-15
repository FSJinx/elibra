<?php

namespace App\Http\Requests;

use App\Models\Holding;
use Illuminate\Contracts\Validation\ValidationRule;
use Override;

class StoreHoldingRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Holding::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reserver_id' => 'nullable|exists:patrons,id',
            'accession_id' => 'nullable|exists:accessions,id',
            'expected_pickup_date' => 'required|date|after_or_equal:today',
        ];
    }
}
