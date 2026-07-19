<?php

namespace App\Http\Requests;

use App\Models\UserPermission;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreUserPermissionRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', UserPermission::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'permission_id' => 'required|exists:permissions,id',
        ];
    }

    /**
     * @return array
     */
    #[Override]
    public function messages():array
    {
        return [
            'user_id.required' => 'User is required.',
            'user_id.exists' => 'The selected User is invalid.',

            'permission_id.required' => 'Permission is required.',
            'permission_id.exists' => 'The selected permission is invalid.'
        ];
    }
}
