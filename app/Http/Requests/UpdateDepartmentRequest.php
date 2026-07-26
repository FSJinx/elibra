<?php

namespace App\Http\Requests;

use App\Models\Campus;
use App\Models\Department;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('department'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [ 'sometimes', 'required', 'string', 'max:255' ],
            'code' => [ 'sometimes', 'required', 'string', 'max:10', 
                         Rule::unique((new Department)->getTable(), 'code')],

            'campus_id' => [ 'sometimes', 'required', 
                             Rule::exists((new Campus())->getTable(), 'id')],
        ];
    } 
}
