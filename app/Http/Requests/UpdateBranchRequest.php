<?php

namespace App\Http\Requests;

use App\Models\Campus;
use App\Models\Librarian;
use App\Models\Media;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('branch'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => [ 'sometimes', 'required', 'string', 'max:255' ],
            'contact_info' => [ 'sometimes', 'nullable', 'string', 'max:255' ],
            'email' => [ 'sometimes', 'nullable', 'email', 'max:255', Rule::unique('branches', 'email')->ignore($this->route('branch')->id) ],

            'opening_hour' => [ 'sometimes', 'nullable', 'date_format:H:i', 'required_with:closing_hour', ],
            'closing_hour' => [ 'sometimes', 'nullable', 'date_format:H:i', 'required_with:opening_hour', 'after:opening_hour' ],
            
            'logo_id' => ['sometimes', 'nullable', Rule::exists((new Media)->getTable(), 'id')],
            'branch_head_id' => ['sometimes', 'nullable', Rule::exists((new Librarian)->getTable(), 'id')],
        ];

        // Only allow super admins to update campus_id
        if($this->user()->isSuperAdmin())
        {
            $rules['campus_id'] = [ 'sometimes', 'required', Rule::exists((new Campus)->getTable(), 'id') ];
        }

        return $rules;
    }
}
