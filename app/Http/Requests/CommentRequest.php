<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function authorize()
    {
        return auth('api')->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|max:3000',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json(['status' => 'Failed', 'data' => $errors], 422)
        );
    }


    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            response()->json(['status' => 'Failed', 'message' => 'user is not logged in!'], 403)
        );
    }
}
