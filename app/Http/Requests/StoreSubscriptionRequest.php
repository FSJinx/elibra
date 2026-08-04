<?php

namespace App\Http\Requests;

use App\Models\Media;
use App\Models\Subscription;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubscriptionRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Subscription::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [ 'required', 'string', 'max:255' ],
            'description' => [ 'nullable', 'string' ],
            'link' => [ 'required', 'url' ],
            'thumbnail_id' => [ 'nullable', 'integer', Rule::exists((new Media)->getTable(), 'id') ],
        ];
    }
}
