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
     * @return bool|\Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard|\Illuminate\Contracts\Foundation\Application
     */
    public function authorize()
    {
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        switch ($this->method()) {
            case 'POST':
                $rules = [
                    'title' => 'required|max:250',
                    'body' => 'required|min:250',
                    'excerpt' => 'required',
                    'is_published' => 'required|bool',
                    'category_ids' => 'required|array|min:1|max:3',
                    'label_ids' => 'max:5',
                    'photo' => 'required|image:jpeg,png,jpg,gif,svg'
                ];
                break;

            case 'PUT':
            case 'PATCH':
                $rules = [
                    'title' => 'required|max:250',
                    'body' => 'required|min:250',
                    'excerpt' => 'required',
                    'is_published' => 'required|bool',
                    'category_ids' => 'required|array|min:1|max:3',
                    'label_ids' => 'max:5',
                    'photo' => 'image:jpeg,png,jpg,gif,svg'
                ];
                break;
        }


        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json(['data' => $errors], 422)
        );
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            response()->json(['message' => 'user is not logged in!'], 401)
        );
    }
}
