<?php

namespace App\Http\Requests;

use App\Models\Media;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreMediaRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Media::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => [ 
                'required', 
                'image',  
                'mimes:jpg,jpeg,png,webp', 
                'max:2048' 
            ],


            'image_type' => [
                'required',
                'in:profile,logo,subscription,book_cover,item_cover,document,banner,other',
            ],

            'item_id' => [
                'nullable',
                'required_if:image_type,item_cover',
                'integer',
                'exists:items,id',
            ],
        ];
    }
}
