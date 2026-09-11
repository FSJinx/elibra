<?php

namespace App\Http\Requests;

use App\Models\Librarian;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateLibrarianRequest extends BaseRequest
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
            'email' => ['sometimes', 'nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('librarian')?->user_id ?? $this->route('librarian')?->user?->id)],
            'username' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->route('librarian')?->user_id)],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],

            'branch_id' => ['sometimes', 'required', Rule::exists('branches', 'id')],
            'role' => ['sometimes', 'nullable', 'string', 'max:255'],
            'tools' => ['sometimes', 'nullable', 'array'],
            'tools.*' => ['string'],
            'librarian_id' => ['sometimes', 'nullable', Rule::exists((new Librarian)->getTable(), 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already in use.',
            'email.unique' => 'This email is already in use.',
            'password.min' => 'Password must be at least 8 characters long.',
            'branch_id.required' => 'Branch is required.',
            'branch_id.exists' => 'Selected branch does not exist.',
        ];
    }
}
