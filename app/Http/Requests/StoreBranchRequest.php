<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends BaseRequest
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
            'name' => 'required|string|max:255',
            'contact_info' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:branches,email',

            'opening_hour' => 'nullable|date_format:H:i',
            'closing_hour' => 'nullable|date_format:H:i|after:opening_hour',
            
            'logo_id' => 'nullable|exists:media,id',
            'branch_head_id' => 'nullable|exists:librarians,id',
            'campus_id' => 'required|exists:campuses,id',

        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Branch name is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email already exists',
            'opening_hour.date_format' => 'Opening hour must be in the format HH:MM',
            'closing_hour.date_format' => 'Closing hour must be in the format HH:MM',
            'closing_hour.after' => 'Closing hour must be after opening hour',
            'logo_id.exists' => 'Selected logo does not exist',
            'branch_head_id.exists' => 'Selected branch head does not exist',
            'campus_id.required' => 'Campus is required',
            'campus_id.exists' => 'Selected campus does not exist',
        ];
    }
}
