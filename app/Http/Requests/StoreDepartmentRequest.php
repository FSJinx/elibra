<?php

namespace App\Http\Requests;

use App\Models\Campus;
use App\Models\Department;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDepartmentRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Department::class);
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
            'code' => [ 'required', 'string', 'max:10', Rule::unique((new Department)->getTable(), 'code') ],
            'campus_id' => [ 'required',  Rule::exists((new Campus)->getTable(), 'id') ]
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
            'name.required' => 'Name is required',
            'code.required' => 'Code is required',
            'code.unique' => 'Code must be unique',
            'code.max' => 'Code must not exceed 10 characters',

            'campus_id.required' => 'Campus is required.',
            'campus_id.exists' => 'The selected campus does not exist.'
        ];
    }
}
