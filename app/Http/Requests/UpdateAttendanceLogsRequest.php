<?php

namespace App\Http\Requests;

use App\Models\Branch;
use App\Models\Patron;
use App\Models\Sections;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateAttendanceLogsRequest extends BaseRequest
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
            'status' => ['sometimes', 'required', Rule::in(['in', 'out'])],
            'patron_id' => ['sometimes', 'required', Rule::exists((new Patron)->getTable(), 'id')],
            'branch_id' => ['sometimes', 'required', Rule::exists((new Branch)->getTable(), 'id')],
            'section_id' => ['sometimes', 'nullable', Rule::exists((new Sections)->getTable(), 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either in or out.',
            'patron_id.required' => 'Patron is required.',
            'patron_id.exists' => 'Selected patron does not exist.',
            'branch_id.required' => 'Branch is required.',
            'branch_id.exists' => 'Selected branch does not exist.',
            'section_id.exists' => 'Selected section does not exist.',
        ];
    }
}
