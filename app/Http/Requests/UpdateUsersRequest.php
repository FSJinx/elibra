<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsersRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->route('user');

        return $this->user()->can('update', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $authUser = $this->user();
        $user = $this->route('user');

        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_initial' => ['nullable', 'string', 'max:2'],
            'sex' => ['required', Rule::in(['male', 'female'])],

            'birthdate' => ['required', 'date', 'before:today'],
            'contact_number' => ['sometimes','required', 'regex:/^09\d{9}$/'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')],

            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:8'],
        ];
        
        if($authUser->isSuperAdmin()){
            $rules['role'] = [
                            'sometimes', 
                            Rule::in(['admin', 'librarian', 'patron'])
                            ];
            $rules['campus_id'] = [
                            'sometimes',
                            'exists:campuses,id'
                            ];
        }

        return $rules;  
    }
}   
