<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreUsersRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $authUser = $this->user();

        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_initial' => ['nullable', 'string', 'max:2'],
            'sex' => ['required', Rule::in(['male', 'female'])],

            'birthdate' => ['required', 'date', 'before:today'],
            'contact_number' => ['required', 'regex:/^09\d{9}$/'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],

            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:8'],
        ];

        //For Super Admin
        if ($authUser->isSuperAdmin()){
            $rules['role'] = [
                            'required', 
                            Rule::in(['admin', 'librarian', 'patron'])
                            ];
            $rules['campus_id'] = [
                            'required',
                            'exists:campuses,id'
                            ];
        // For Library Admin
        }elseif($authUser->isAdmin()){
            $rules['role'] = [
                            'required', 
                            Rule::in(['librarian', 'patron'])
                            ];
        }
        
        return $rules;
    }
}
