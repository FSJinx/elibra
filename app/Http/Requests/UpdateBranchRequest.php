<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends BaseRequest
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
        $rules = [
            'name' => 'sometimes|required|string|max:255',
            'contact_info' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|nullable|email|max:255|unique:branches,email,' . $this->route('branch')->id,
            'opening_hour' => 'sometimes|nullable|date_format:H:i',
            'closing_hour' => 'sometimes|nullable|date_format:H:i|after:opening_hour',
            'logo_id' => 'sometimes|nullable|exists:media,id',
            'branch_head_id' => 'sometimes|nullable|exists:librarians,id',
        ];

        // Only allow admins to update campus_id
        if($this->user()->isAdmin()){
            $rules['campus_id'] = 'sometimes|required|exists:campuses,id';
        }

        return $rules;
    }
}
