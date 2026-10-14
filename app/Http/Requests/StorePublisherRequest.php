<?php

namespace App\Http\Requests;

use App\Models\Publisher;
use Illuminate\Contracts\Validation\ValidationRule;
use Override;

class StorePublisherRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Publisher::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'name.required' => 'Name of publisher is required,',
        ];
    }
}
