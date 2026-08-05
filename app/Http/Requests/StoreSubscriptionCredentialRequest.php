<?php

namespace App\Http\Requests;

use App\Models\Campus;
use App\Models\Subscription;
use App\Models\SubscriptionCredential;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreSubscriptionCredentialRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', SubscriptionCredential::class);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => [ 'required', 'string', 'max:255' ],
            'password' => [ 'required', 'string', 'max:255' ],

            'subscription_id' => [ 'required', 'integer', Rule::exists((new Subscription)->getTable(), 'id') ],
            'campus_id' => [ 
                    Rule::requiredIf(fn () => $this->user()->isSuperAdmin()),
                    Rule::exists((new Campus)->getTable(), 'id') ],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->user()->isAdmin() || $this->user()->isLibrarian()) {
            $this->merge([
                'campus_id' => $this->user()->campus_id,
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'username.required' => 'The username field is required.',
            'password.required' => 'The password field is required.',
            'subscription_id.exists' => 'The selected subscription does not exist.',
            'campus_id.exists' => 'The selected campus does not exist.',
        ];
    }
}
