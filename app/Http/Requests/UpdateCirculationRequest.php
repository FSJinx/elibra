<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateCirculationRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && ($user->isLibrarian() || $user->isAdmin() || $user->isSuperAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['borrowed', 'returned', 'overdue', 'lost', 'cancelled'])],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'A loan status is required.',
            'status.in' => 'The status must be one of: borrowed, returned, overdue, lost, or cancelled.',
        ];
    }
}
