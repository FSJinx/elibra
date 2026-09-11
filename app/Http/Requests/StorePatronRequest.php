<?php

namespace App\Http\Requests;

use App\Models\Patron;
use App\Models\PatronType;
use App\Models\Programs;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StorePatronRequest extends BaseRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_initial' => ['nullable', 'string', 'max:2'],
            'sex' => ['nullable', 'in:male,female'],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:8'],

            'patron_type_id' => ['required', Rule::exists((new PatronType)->getTable(), 'id')],
            'program_id' => ['nullable', Rule::exists((new Programs)->getTable(), 'id')],
            'ebc_number' => ['nullable', 'string', Rule::unique((new Patron)->getTable(), 'ebc_number')],
            'external_organization' => ['nullable', 'string', 'max:255'],
            'date_joined' => ['nullable', 'date'],
            'account_expiry' => ['nullable', 'date', 'after_or_equal:date_joined'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already in use.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'patron_type_id.required' => 'Patron type is required.',
            'patron_type_id.exists' => 'Selected patron type does not exist.',
            'program_id.exists' => 'Selected program does not exist.',
            'ebc_number.unique' => 'This EBC number is already in use.',
            'account_expiry.after_or_equal' => 'Account expiry must be on or after the join date.',
        ];
    }
}
