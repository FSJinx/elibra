<?php

namespace App\Http\Requests;

use App\Models\Librarian;
use App\Models\Library;
use App\Models\Sections;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateSectionsRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('section'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique((new Sections)->getTable(), 'name')->ignore($this->route('section'))],
            'librarian_id' => ['sometimes'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user();
            $section = $this->route('section');

            if ($user?->isAdmin() && ! Library::query()
                ->whereKey($section->library_id)
                ->where('campus_id', $user->campus_id)
                ->exists()) {
                $validator->errors()->add('section', 'This section is not in your campus.');
            }

            $librarianId = $this->input('librarian_id');
            if (! $librarianId) {
                return;
            }

            if (! Librarian::query()
                ->whereKey($librarianId)
                ->where('library_id', $section->library_id)
                ->exists()) {
                $validator->errors()->add('librarian_id', 'The selected section head is not assigned to this library.');
            }
        });
    }
}
