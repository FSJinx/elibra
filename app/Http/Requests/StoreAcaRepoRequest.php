<?php

namespace App\Http\Requests;

use App\Models\Language;
use App\Models\Library;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Override;

class StoreAcaRepoRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && (
            ($user->isPatron() && $user->patron()->exists())
            || $user->isSuperAdmin() || $user->isAdmin() || $user->isLibrarian()
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $libraryRule = Rule::exists((new Library)->getTable(), 'id');
        if ($this->user()->isPatron()) {
            $libraryRule->where('campus_id', $this->user()->campus_id);
        }

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(['ut', 'gt'])],
            'description' => ['required', 'string'],
            'doi' => ['nullable', 'string', 'max:255'],
            'electronic_file' => $this->hasFile('electronic_file') ? ['file', 'required', 'mimes:pdf', 'max:20480'] : ['required', 'string'],
            'library_id' => [$this->isMethod('POST') ? 'required' : 'sometimes', $libraryRule],
            'keywords' => ['required', 'array'],
            'keywords.*' => ['string', 'max:255'],
            'language_id' => ['required', Rule::exists((new Language)->getTable(), 'id')],
        ];

        if (! $this->user()->isPatron()) {
            $rules['language_id'] = ['nullable', Rule::exists((new Language)->getTable(), 'id')];
            $rules['program_id'] = ['nullable', 'exists:programs,id'];
            $rules['status'] = ['required', Rule::in(['pending', 'approved', 'rejected'])];
            $rules['statusRemark'] = ['nullable', 'string', 'max:255'];
            $rules['on_catalog'] = ['required', 'boolean'];
        }

        return $rules;
    }

    #[Override]
    public function messages(): array
    {
        return [
            'electronic_file.mimes' => 'Please convert your file to PDF or upload only PDF files.',
            'library_id.exists' => 'The selected library does not exist or is not assigned to your campus. Please select a library from your campus.',
        ];
    }
}
