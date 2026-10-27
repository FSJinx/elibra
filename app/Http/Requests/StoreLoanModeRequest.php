<?php

namespace App\Http\Requests;

use App\Models\LoanMode;
use Illuminate\Contracts\Validation\ValidationRule;
use Override;

class StoreLoanModeRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user->can('create', LoanMode::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'slug' => 'required|unique:loan_modes,slug',
            'name' => 'required',
            'description' => 'sometimes',
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'slug.unique' => 'The provided code is already in use.',
        ];
    }
}
