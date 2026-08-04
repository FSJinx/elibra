<?php

namespace App\Http\Requests;

use App\Models\Sections;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSectionsRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Sections::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique((new Sections)->getTable(), 'name'),
            ]
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
            'name.required' => 'Section name is required',
            'name.unique' => 'Section name has already been taken',
        ];
    }
}
