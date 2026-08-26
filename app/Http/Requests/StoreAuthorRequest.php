<?php

namespace App\Http\Requests;

use App\Models\Author;
use Illuminate\Contracts\Validation\ValidationRule;
use Override;

class StoreAuthorRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Author::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [ 'required', 'string', 'max:255' ]
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'name.required' => 'Name of author is requireed.',
        ];
    }
}
