<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CatalogSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query' => [
                'nullable',
                'string',
                'max:255',
            ],

            'campus_id' => [
                'nullable',
                'integer',
                'exists:campuses,id',
            ],

            'branch_id' => [
                'nullable',
                'integer',
                'exists:branches,id',
            ],

            'item_type_id' => [
                'nullable',
                'integer',
                'exists:item_types,id',
            ],

            'item_type_category_id' => [
                'nullable',
                'integer',
                'exists:item_type_categories,id',
            ],

            'department_id' => [
                'nullable',
                'integer',
                'exists:departments,id',
            ],

            'publication_year' => [
                'nullable',
                'integer',
                'min:1000',
                'max:9999',
            ],

            'order' => [
                'nullable',
                'string',
                'in:asc,desc',
            ],

            'sort' => [
                'nullable',
                'string',
                'in:title,author,publisher,publication_year',
            ],
            
            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:50',
            ],
        ];
    }
}