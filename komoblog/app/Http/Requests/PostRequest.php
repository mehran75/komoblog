<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use function Symfony\Component\VarDumper\Dumper\esc;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:250',
            'body' => 'required|min:250',
            'excerpt' => 'required',
            'is_published' => 'required|bool',
            'category_ids' => 'required|array|min:1',
            'image_id' => 'required',
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
            response()->json(['status' => 'Failed', 'message'=> 'user is not logged in!'], 403)
        );
    }
}
