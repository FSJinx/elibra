<?php

namespace App\Http\Requests;

use App\Models\AcquisitionRequest;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreAcquisitionRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return 
            $user->hasPermission('acquisition.create') && ($user->isLibrarian() && $user->librarian->branch)
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
            'purchaseId' => [ 'nullable', 'string', 'max:255', 'unique:acquisitions,purchaseId'],
            'dealer' => [ 'required', 'string', 'max:255', ], 
            'acquisition_mode' => [ 'required', Rule::in([ 'purchased', 'donated', 'gift', 'exchange', ]), ], 
            'acquisition_date' => [ 'required', 'date', ], 
            'remarks' => [ 'nullable', 'string', 'max:255', ],

            'receiver_user_id' => [ 'required', 'integer', Rule::exists((new User)->getTable(), 'id')],
            'acquisition_request_id' => ['nullable', 'integer', Rule::exists((new AcquisitionRequest)->getTable(), 'id')],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'purchaseId.string' => 'The purchase ID must be a valid text value.',
            'purchaseId.max' => 'The purchase ID may not exceed 255 characters.',
            'purchaseId.unique' => 'This purchase ID has already been used.',

            'dealer.required' => 'The dealer is required.',
            'dealer.string' => 'The dealer must be a valid text value.',
            'dealer.max' => 'The dealer may not exceed 255 characters.',

            'acquisition_mode.required' => 'The acquisition mode is required.',
            'acquisition_mode.in' => 'The selected acquisition mode is invalid. Please choose purchased, donated, gift, or exchange.',

            'acquisition_date.required' => 'The acquisition date is required.',
            'acquisition_date.date' => 'The acquisition date must be a valid date.',

            'remarks.string' => 'The remarks must be a valid text value.',
            'remarks.max' => 'The remarks may not exceed 255 characters.',

            'receiver_user_id.required' => 'The receiver user is required.',
            'receiver_user_id.integer' => 'The receiver user ID must be a valid number.',
            'receiver_user_id.exists' => 'The selected receiver user does not exist.',

            'acquisition_request_id.integer' => 'The acquisition request ID must be a valid number.',
            'acquisition_request_id.exists' => 'The selected acquisition request does not exist.',
        ];
    }
}
