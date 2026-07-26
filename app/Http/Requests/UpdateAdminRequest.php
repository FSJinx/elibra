<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Override;

class UpdateAdminRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return $this->user()->can('update', User::class);
        return $this->user()->can('update', $this->route('admin'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $admin = $this->route('admin');

        return [
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'middle_initial' => ['sometimes', 'nullable', 'string', 'max:2'],
            'sex' => ['sometimes', 'required', Rule::in(['male', 'female'])],

            'birthdate' => ['sometimes', 'required', 'date', 'before:today'],
            'contact_number' => ['sometimes', 'required', 'regex:/^09\d{9}$/'],

            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($admin)],

            'username' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($admin)],

            'password' => ['sometimes', 'required', 'string', 'min:8'],
            // 'role' => ['sometimes', 'required', Rule::in(['admin'])],

            'profile_picture_id' => ['sometimes', 'required', 'exists:media,id'],
            'campus_id' => ['sometimes', 'required', 'exists:campuses,id'],
        ];
        
    }
}
