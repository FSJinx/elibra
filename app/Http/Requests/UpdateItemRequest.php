<?php

namespace App\Http\Requests;

use App\Models\Author;
use App\Models\Authorship;
use App\Models\Department;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\ItemTypeCategory;
use App\Models\Language;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $item = $this->route('item');

        if (! $user || ! $item instanceof Item) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            return $item->library()->where('campus_id', $user->campus_id)->exists();
        }

        $libraryId = $user->librarian?->library_id;

        return $user->isLibrarian()
            && $libraryId !== null
            && (int) $libraryId === (int) $item->library_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'subtitle' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'call_number' => ['sometimes', 'nullable', 'string', 'max:255'],
            'keywords' => ['sometimes', 'nullable', 'array'],
            'language_id' => ['sometimes', 'required', Rule::exists((new Language)->getTable(), 'id')],
            'electronic_file' => ['sometimes', 'file', 'mimes:pdf,doc,docx'],
            'cover_image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'item_type_id' => ['sometimes', 'required', Rule::exists((new ItemType)->getTable(), 'id')],
            'item_type_category_id' => ['sometimes', 'required', Rule::exists((new ItemTypeCategory)->getTable(), 'id')],
            'edition' => ['sometimes', 'nullable', 'string', 'max:255'],
            'isbn_issn' => ['sometimes', 'nullable', 'string', 'max:255'],
            'doi' => ['sometimes', 'nullable', 'string', 'max:255'],
            'volume' => ['sometimes', 'nullable', 'string', 'max:255'],
            'issue' => ['sometimes', 'nullable', 'string', 'max:255'],
            'pages' => ['sometimes', 'nullable', 'string', 'max:255'],
            'department_id' => ['sometimes', 'nullable', Rule::exists((new Department)->getTable(), 'id')],
            'authors' => ['sometimes', 'nullable', 'array'],
            'authors.*.id' => ['required', 'integer', Rule::exists((new Author)->getTable(), 'id')->whereNull('deleted_at')],
            'authors.*.authorship_id' => ['nullable', 'integer', Rule::exists((new Authorship)->getTable(), 'id')],
            'authors_present' => ['sometimes', 'boolean'],

            'released' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        foreach (['authors', 'keywords'] as $field) {
            if ($this->input($field) === '[]') {
                $this->merge([$field => []]);
            }
        }
    }
}
