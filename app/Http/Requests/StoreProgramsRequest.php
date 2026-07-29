<?php

namespace App\Http\Requests;

use App\Models\Department;
use App\Models\Programs;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProgramsRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Programs::class);
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
            'code' => [ 'required', 'string', 'max:10' ],
            'department_id' => [ 'required', Rule::exists((new Department)->getTable(), 'id') ]
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
            'code.max' => 'Code must not exceed 10 characters',

            'department_id.required' => 'Department is required.',
            'department_id.exists' => 'The selected department does not exist.'
        ];
    }
}
