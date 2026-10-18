<?php

namespace App\Http\Requests;

use App\Models\Language;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreAcaRepoRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null
            && $user->isPatron()
            && $user->patron()->exists();
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
            'category' => ['required', Rule::in(['ut', 'gt'])],
            'description' => ['required', 'string'],
            'doi' => ['nullable', 'string', 'max:255'],
            'keywords' => ['required', 'array'],
            'keywords.*' => ['string', 'max:255'],
            'language_id' => ['required', Rule::exists((new Language)->getTable(), 'id')],
        ];
    }
}
