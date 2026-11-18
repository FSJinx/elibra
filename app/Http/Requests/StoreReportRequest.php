<?php

namespace App\Http\Requests;

use App\Models\Report;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreReportRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Report::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['sometimes', Rule::in(array_map(fn ($case) => $case->value, \App\Enums\ReportType::cases()))],
            'format' => 'required|in:pdf,xlsx,csv',
            'filters' => 'sometimes|array',
            'created_from' => 'sometimes|nullable|date_format:Y-m-d|before_or_equal:'.($this->filled('created_to') ? 'created_to' : 'today'),
            'created_to' => 'sometimes|nullable|date_format:Y-m-d|before_or_equal:today|after_or_equal:created_from',
        ];
    }
}
