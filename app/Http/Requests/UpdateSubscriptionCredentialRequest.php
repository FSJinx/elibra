<?php

namespace App\Http\Requests;

use App\Models\Campus;
use App\Models\Subscription;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubscriptionCredentialRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('subscriptionCredential'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'username' => [ 'sometimes', 'required', 'string', 'max:255' ],
            'password' => [ 'sometimes', 'required', 'string', 'max:255' ],

            'subscription_id' => [ 'sometimes', 'required', 'integer', Rule::exists((new Subscription)->getTable(), 'id') ],
        ];

        if($this->user()->isSuperAdmin()) {
            $rules['campus_id'] = [ 'sometimes', 'required', 'integer', Rule::exists((new Campus)->getTable(), 'id') ];
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->user()->isSuperAdmin()) {
            $this->request->remove('campus_id');
        }
    } // This is to ensure that non-super-admin users cannot change the campus_id of a subscription credential.
}
