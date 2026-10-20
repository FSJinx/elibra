<?php

namespace App\Http\Requests;

use App\Models\Author;
use App\Models\Authorship;
use App\Models\Department;
use App\Models\ItemType;
use App\Models\ItemTypeCategory;
use App\Models\Language;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreItemRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user->isSuperAdmin() || $user->isAdmin() || $user->isLibrarian();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'call_number' => ['nullable', 'string', 'max:255'],
            'keywords' => ['nullable', 'array'],
            'language_id' => ['required', Rule::exists((new Language)->getTable(), 'id')],
            'electronic_file' => [
                Rule::prohibitedIf(fn (): bool => ! ItemType::query()
                    ->whereKey($this->input('item_type_id'))
                    ->whereIn('slug', ['book', 'thesis'])
                    ->exists()),
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
            ],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'item_type_id' => ['required', Rule::exists((new ItemType)->getTable(), 'id')],
            'item_type_category_id' => ['required', Rule::exists((new ItemTypeCategory)->getTable(), 'id')],
            'edition' => ['nullable', 'string', 'max:255'],
            'isbn_issn' => ['nullable', 'string', 'max:255'],
            'doi' => ['nullable', 'string', 'max:255'],
            'volume' => ['nullable', 'string', 'max:255'],
            'issue' => ['nullable', 'string', 'max:255'],
            'pages' => ['nullable', 'string', 'max:255'],
            'department_id' => ['nullable', Rule::exists((new Department)->getTable(), 'id')],
            'authors' => ['nullable', 'array'],
            'authors.*.id' => ['nullable', 'integer', Rule::exists((new Author)->getTable(), 'id')->whereNull('deleted_at')],
            'authors.*.authorship_id' => ['nullable', 'integer', Rule::exists((new Authorship)->getTable(), 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'electronic_file.prohibited' => 'An electronic file is only allowed for books and theses.',
            'electronic_file.file' => 'The electronic file must be a valid file.',
            'electronic_file.mimes' => 'The electronic file must be a PDF, DOC, or DOCX file.',
        ];
    }
}
