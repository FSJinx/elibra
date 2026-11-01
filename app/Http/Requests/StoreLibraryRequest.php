<?php

namespace App\Http\Requests;

use App\Models\Campus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreLibraryRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isSuperAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'regex:/^(09\d{9}|639\d{9})$/'],
            'email' => ['required', 'email', 'max:255', Rule::unique('libraries', 'email')],
            'campus_id' => ['required', Rule::exists((new Campus)->getTable(), 'id')],
            'website' => ['nullable', 'url'],
            'opening_hour' => ['required', 'date_format:H:i,H:i:s', 'required_with:closing_hour'],
            'closing_hour' => ['required', 'date_format:H:i,H:i:s', 'required_with:opening_hour', 'after:opening_hour'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Library name is required.',
            'phone.regex' => 'Enter a Philippine mobile number as 09171234567 or 639171234567.',
            'email.required' => 'Library email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'campus_id.required' => 'Campus is required.',
            'campus_id.exists' => 'The selected campus is invalid.',
            'opening_hour.required' => 'Opening hour is required.',
            'opening_hour.date_format' => 'Opening hour must be in the format HH:MM.',
            'closing_hour.required' => 'Closing hour is required.',
            'closing_hour.date_format' => 'Closing hour must be in the format HH:MM.',
            'closing_hour.after' => 'Closing hour must be later than the opening hour.',
        ];
    }
}
