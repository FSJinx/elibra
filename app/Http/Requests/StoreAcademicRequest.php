<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user || !$user->librarian) {
            return false;
        }

        $librarian = $user->librarian;
        if (!$librarian->primary_role || strtolower($librarian->primary_role->name) !== 'academics') {
            return false;
        }

        return true;
        // dd(
        //     $this->user()->librarian,
        //     optional($this->user()->librarian)->primary_role
        // );
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
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',

            // Academic table fields
            'call_number' => 'required|string|max:255|unique:academics,call_number',
            'language' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'subjects' => 'nullable|array',
            'subjects.*' => 'string|max:255',
            'department_id' => 'required|exists:departments,id',
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
            'title.required' => 'Title is required',
            'call_number.required' => 'Call number is required',
            'call_number.unique' => 'Call number already exists',
            'language.required' => 'Language is required',
            'category.required' => 'Category is required',
            'department_id.required' => 'Department is required',
            'department_id.exists' => 'Selected department does not exist',
        ];
    }
}
