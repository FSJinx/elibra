<?php

namespace App\Http\Requests;

use App\Models\Patron;
use App\Models\PatronType;
use App\Models\Programs;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdatePatronRequest extends BaseRequest
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
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'middle_initial' => ['sometimes', 'nullable', 'string', 'max:2'],
            'sex' => ['sometimes', 'nullable', 'in:male,female'],
            'birthdate' => ['sometimes', 'nullable', 'date', 'before:today'],
            'contact_number' => ['sometimes', 'nullable', 'string', 'max:20'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('patron')?->user_id ?? $this->route('patron')?->user?->id)],
            'username' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->route('patron')?->user_id)],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],

            'patron_type_id' => ['sometimes', 'required', Rule::exists((new PatronType)->getTable(), 'id')],
            'program_id' => ['sometimes', 'nullable', Rule::exists((new Programs)->getTable(), 'id')],
            'ebc_number' => ['sometimes', 'nullable', 'string', Rule::unique((new Patron)->getTable(), 'ebc_number')->ignore($this->route('patron')->id)],
            'external_organization' => ['sometimes', 'nullable', 'string', 'max:255'],
            'date_joined' => ['sometimes', 'nullable', 'date'],
            'account_expiry' => ['sometimes', 'nullable', 'date', 'after_or_equal:date_joined'],
            'remarks' => ['sometimes', 'nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already in use.',
            'password.min' => 'Password must be at least 8 characters long.',
            'patron_type_id.required' => 'Patron type is required.',
            'patron_type_id.exists' => 'Selected patron type does not exist.',
            'program_id.exists' => 'Selected program does not exist.',
            'ebc_number.unique' => 'This EBC number is already in use.',
            'account_expiry.after_or_equal' => 'Account expiry must be on or after the join date.',
        ];
    }
}
