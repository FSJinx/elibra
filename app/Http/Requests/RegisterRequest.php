<?php

namespace App\Http\Requests;

use App\Models\PatronType;
use App\Models\Campus;
use App\Models\Programs;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class RegisterRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_initial' => ['nullable', 'string', 'max:2'],
            'sex' => ['required', Rule::in(['male', 'female'])],
            'birthdate' => ['required', 'date', 'before:today'],
            'contact_number' => ['required', 'regex:/^09\d{9}$/'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'patron_type_id' => ['required', 'integer', Rule::exists((new PatronType)->getTable(), 'id')],
            'campus_id' => ['required', 'integer', Rule::exists((new Campus)->getTable(), 'id')],
            'program_id' => [
                'required',
                'integer',
                Rule::exists((new Programs)->getTable(), 'id')->where(
                    fn ($query) => $query->whereIn(
                        'department_id',
                        function ($departmentQuery) {
                            $departmentQuery->select('id')
                                ->from('departments')
                                ->where('campus_id', $this->input('campus_id'));
                        }
                    )
                ),
            ],
            'profile_picture' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ];
    }
}
