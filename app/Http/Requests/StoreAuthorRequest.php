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
            'first_name' => [ 'required', 'string', 'max:255' ],
            'middle_name' => [ 'required', 'string', 'max:255' ],
            'last_name' => [ 'required', 'string', 'max:255' ],
            'suffix' => [ 'required', 'string', 'max:10' ],
        ];
    }

    #[Override]
    public function messages()
    {
        $messages = [
            'first_name.required' => 'First name of author is required.',
            'last_name.required' => 'Last name of author is required.',
        ];

        return $messages;
    }
}
