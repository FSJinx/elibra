<?php

namespace App\Http\Requests;

use App\Models\AcquisitionRequest;
use App\Models\Librarian;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreAcquisitionRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null
            && (($user->isLibrarian() && $user->librarian?->library !== null) || $user->isAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'acquisition_id' => ['nullable', 'string', 'max:255', 'unique:acquisitions,acquisition_id'],
            'dealer' => ['required', 'string', 'max:255'],
            'acquisition_mode' => ['required', Rule::in(['purchase', 'donation', 'gift', 'exchange'])],
            'acquisition_date' => ['required', 'date'],
            'remarks' => ['nullable', 'string', 'max:255'],

            'receiver_id' => ['required', 'integer', Rule::exists((new Librarian)->getTable(), 'id')],
            'acquisition_request_id' => ['nullable', 'integer', Rule::exists((new AcquisitionRequest)->getTable(), 'id')],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $receiverId = $this->input('receiver_id');

            if (! $receiverId) {
                return;
            }

            $user = $this->user();
            $query = Librarian::query()->whereKey($receiverId);

            if ($user->isAdmin()) {
                $query->whereHas('library', function ($query) use ($user) {
                    $query->where('campus_id', $user->campus_id);
                });
            } elseif ($user->isLibrarian()) {
                $query->where('library_id', $user->librarian?->library_id);
            }

            if (! $query->exists()) {
                $validator->errors()->add('receiver_id', 'The selected librarian is not available for this library.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'acquisition_id.string' => 'The acquisition ID must be a valid text value.',
            'acquisition_id.max' => 'The acquisition ID may not exceed 255 characters.',
            'acquisition_id.unique' => 'This acquisition ID has already been used.',

            'dealer.required' => 'The dealer is required.',
            'dealer.string' => 'The dealer must be a valid text value.',
            'dealer.max' => 'The dealer may not exceed 255 characters.',

            'acquisition_mode.required' => 'The acquisition mode is required.',
            'acquisition_mode.in' => 'The selected acquisition mode is invalid.',

            'acquisition_date.required' => 'The acquisition date is required.',
            'acquisition_date.date' => 'The acquisition date must be a valid date.',

            'remarks.string' => 'The remarks must be a valid text value.',
            'remarks.max' => 'The remarks may not exceed 255 characters.',

            'receiver_id.required' => 'A librarian is required.',
            'receiver_id.integer' => 'The librarian selection is invalid.',
            'receiver_id.exists' => 'The selected librarian does not exist.',

            'acquisition_request_id.integer' => 'The acquisition request ID must be a valid number.',
            'acquisition_request_id.exists' => 'The selected acquisition request does not exist.',
        ];
    }
}
