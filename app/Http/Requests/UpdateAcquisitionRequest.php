<?php

namespace App\Http\Requests;

use App\Models\AcquisitionRequest;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateAcquisitionRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
       $user = $this->user();

        return 
            $user->hasPermission('acquisition.update') && ($user->isLibrarian() && $user->librarian->branch)
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
            'acquisition_id' => [ 'sometimes', 'nullable', 'string', 'max:255', 'unique:acquisitions,acquisition_id'],
            'dealer' => [ 'sometimes', 'required', 'string', 'max:255', ], 
            'acquisition_mode' => [ 'sometimes', 'required', Rule::in([ 'purchased', 'donated', 'gift', 'exchange', ]), ], 
            'acquisition_date' => [ 'sometimes', 'required', 'date', ], 
            'remarks' => [ 'sometimes', 'nullable', 'string', 'max:255', ],

            'receiver_user_id' => [ 'sometimes', 'required', 'integer', Rule::exists((new User)->getTable(), 'id')],
            'acquisition_request_id' => ['sometimes', 'nullable', 'integer', Rule::exists((new AcquisitionRequest)->getTable(), 'id')],
        ];

        return $rules;
    }
}
