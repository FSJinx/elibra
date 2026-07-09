<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAcademicRequest extends FormRequest
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
        return [
            // Item table fields
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',

            // Academic table fields
            'call_number' => 'sometimes|required|string|max:255|unique:academics,call_number,' . $this->route('academic')->id,
            'language' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:255',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'subjects' => 'nullable|array',
            'subjects.*' => 'string|max:255',
            'department_id' => 'sometimes|required|exists:departments,id',
        ];
    }
}
