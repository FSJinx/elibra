<?php

namespace App\Http\Requests;

use App\Models\Sections;
use App\Models\Library;
use App\Models\Librarian;
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

    protected function prepareForValidation(): void
    {
        if ($this->user()?->isLibrarian()) {
            $this->merge([
                'library_id' => $this->user()->librarian?->library_id,
            ]);
        }
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
            ],
            'library_id' => ['required', 'integer', Rule::exists((new Library)->getTable(), 'id')],
            'librarian_id' => ['nullable', 'integer', Rule::exists((new Librarian)->getTable(), 'id')],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user();
            if ($user?->isAdmin() && ! Library::query()
                ->whereKey($this->input('library_id'))
                ->where('campus_id', $user->campus_id)
                ->exists()) {
                $validator->errors()->add('library_id', 'The selected library is not in your campus.');
            }

            $librarianId = $this->input('librarian_id');
            if ($librarianId && ! Librarian::query()
                ->whereKey($librarianId)
                ->where('library_id', $this->input('library_id'))
                ->exists()) {
                $validator->errors()->add('librarian_id', 'The selected section head does not belong to this library.');
            }
        });
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
            'library_id.required' => 'A library is required for this section.',
            'library_id.exists' => 'The selected library does not exist.',
        ];
    }
}
