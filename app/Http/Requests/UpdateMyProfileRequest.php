<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class UpdateMyProfileRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $user = $this->user();

        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'middle_initial' => ['nullable', 'string', 'max:2'],
            'sex' => ['sometimes', Rule::in(['male', 'female'])],
            'birthdate' => ['sometimes', 'date', 'before:today'],
            'contact_number' => ['sometimes', 'regex:/^09\\d{9}$/'],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'username' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
            'profile_picture' => [
                'sometimes',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ];
    }

    // protected function withValidator(Validator $validator): void
    // {
    //     $validator->after(function (Validator $validator) {
    //         if ($this->collect()->only([
    //             'first_name',
    //             'last_name',
    //             'middle_initial',
    //             'sex',
    //             'birthdate',
    //             'contact_number',
    //             'email',
    //             'username',
    //             'password',
    //             'profile_picture',
    //         ])->isEmpty()) {
    //             $validator->errors()->add(
    //                 'profile',
    //                 'At least one profile field is required.'
    //             );
    //         }
    //     });
    // }
}
