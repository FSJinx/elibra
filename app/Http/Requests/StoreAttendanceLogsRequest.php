<?php

namespace App\Http\Requests;

use App\Models\Branch;
use App\Models\Patron;
use App\Models\Sections;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreAttendanceLogsRequest extends BaseRequest
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
            'status' => ['required', Rule::in(['in', 'out'])],
            'patron_id' => ['sometimes', 'nullable', Rule::exists((new Patron)->getTable(), 'id')],
            'username' => ['sometimes', 'nullable', 'string', Rule::exists((new User)->getTable(), 'username')],
            'branch_id' => ['required', Rule::exists((new Branch)->getTable(), 'id')],
            'section_id' => ['nullable', Rule::exists((new Sections)->getTable(), 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either in or out.',
            'patron_id.exists' => 'Selected patron does not exist.',
            'username.exists' => 'This username is not registered as a patron.',
            'branch_id.required' => 'Branch is required.',
            'branch_id.exists' => 'Selected branch does not exist.',
            'section_id.exists' => 'Selected section does not exist.',
        ];
    }

    public function after(): array
    {
        return [
            function ($validator) {
                $hasPatronId = filled($this->input('patron_id'));
                $hasUsername = filled($this->input('username'));

                if (! $hasPatronId && ! $hasUsername) {
                    $validator->errors()->add('patron_id', 'Patron is required.');
                    $validator->errors()->add('username', 'Username is required.');
                }
            },
        ];
    }
}
