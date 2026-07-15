<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Override;

class BaseRequest extends FormRequest
{
    #[Override]
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to make this action, please contact your administrator to ask privilege.',
            ], 403)
        );
    }

    #[Override]
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'The given data was invalid.',
                // .errors() ay magbabalik ng key-value pair (e.g., 'username' => ['The username has already been taken.'])
                'errors' => $validator->errors(),
            ], 422) // 422 ang standard code para sa Validation Errors
        );
    }
}
