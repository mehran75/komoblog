<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Only 'admin' user can make changes on a category
     *
     * @return bool
     */
    public function authorize()
    {
//        not the best approach
        $user = auth('api')->user();
        return $user != null && $user->role == 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->method() == 'DELETE') {
            return [];
        }
        return [
            'name' => ['required', 'max:150']
        ];
    }

}
