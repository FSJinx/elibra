<?php

namespace App\Http\Requests;

use App\Models\Department;
use App\Models\Programs;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProgramsRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('program'));
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
            'code' => [ 'sometimes', 'required', 'string', 'max:10' ],

            'department_id' => [ 'sometimes', 'required',
                                  Rule::exists((new Department)->getTable(), 'id') ],
 
        ];
    }
}
