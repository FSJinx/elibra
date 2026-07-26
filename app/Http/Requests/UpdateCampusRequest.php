<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCampusRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('campus'));
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
            'code' => [ 'sometimes','required','string','max:10',
                        Rule::unique('campuses', 'code')->ignore($this->route('campus')->id),
                      ],
            'address' => [ 'sometimes', 'required', 'string','max:255' ],
            'status' => [ 'sometimes', 'required', Rule::in(['active', 'inactive']) ],

        ];
    }
}
    