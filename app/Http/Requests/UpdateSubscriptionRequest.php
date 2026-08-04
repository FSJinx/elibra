<?php

namespace App\Http\Requests;

use App\Models\Media;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubscriptionRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('subscription'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [ 'sometimes', 'required', 'string', 'max:255' ],
            'description' => [ 'sometimes', 'nullable', 'string' ],
            'link' => [ 'sometimes', 'required', 'url' ],
            'thumbnail_id' => [ 'sometimes', 'nullable', 'integer', Rule::exists((new Media)->getTable(), 'id') ],
        ]; 
    }
}
