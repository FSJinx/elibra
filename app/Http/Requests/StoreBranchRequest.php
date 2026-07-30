<?php

namespace App\Http\Requests;

use App\Models\Branch;
use App\Models\Campus;
use App\Models\Librarian;
use App\Models\Media;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreBranchRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Branch::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [ 'required', 'string', 'max:255' ],
            'contact_info' => [ 'nullable', 'string', 'max:255' ],
            'email' => [ 'nullable', 'email', 'max:255', Rule::unique('branches', 'email') ],

            'opening_hour' => [ 'nullable', 'date_format:H:i', 'required_with:closing_hour', ],
            'closing_hour' => [ 'nullable', 'date_format:H:i', 'required_with:opening_hour', 'after:opening_hour' ],

            'logo_id' => [ 'nullable', Rule::exists((new Media)->getTable(), 'id') ],
            'branch_head_id' => [ 'nullable',  Rule::exists((new Librarian)->getTable(), 'id') ],
            'campus_id' => [ 'required', Rule::exists((new Campus)->getTable(), 'id') ]
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
            'name.required' => 'Branch name is required.',

            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',

            'opening_hour.date_format' => 'Opening hour must be in the format HH:MM.',
            'closing_hour.date_format' => 'Closing hour must be in the format HH:MM.',
            'closing_hour.after' => 'Closing hour must be later than the opening hour.',

            'opening_hour.required_with' => 'Opening hour is required when a closing hour is provided.',
            'closing_hour.required_with' => 'Closing hour is required when an opening hour is provided.',

            'logo_id.exists' => 'The selected logo is invalid.',
            'branch_head_id.exists' => 'The selected branch head is invalid.',

            'campus_id.required' => 'Campus is required.',
            'campus_id.exists' => 'The selected campus is invalid.',
        ];
    }
}
