<?php

namespace App\Http\Requests;

use App\Models\ItemType;
use App\Models\ItemTypeCategory;
use App\Models\Language;
use App\Models\Library;
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

        return $user->isAdmin() || $user->isLibrarian();
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
            'publication_year' => ['nullable', 'integer', 'min:1900', 'max:'.date('Y')],
            'keywords' => ['nullable', 'array'],
            'language_id' => ['required', Rule::exists((new Language)->getTable(), 'id')],
            'electronic_file' => ['nullable', 'file', 'mimes:pdf,doc,docx'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'item_type_id' => ['required', Rule::exists((new ItemType)->getTable(), 'id')],
            'item_type_category_id' => ['required', Rule::exists((new ItemTypeCategory)->getTable(), 'id')],
            // 'library_id' => ['required', Rule::exists((new Library)->getTable(), 'id')],
        ];
    }
}
