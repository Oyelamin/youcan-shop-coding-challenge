<?php
/**
 * Created by PhpStorm.
 * User: blessing
 * Date: 24/08/2023
 * Time: 12:16 am
 */

namespace App\Support\Abstracts;

use App\Support\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class ApiRequestConfig extends FormRequest
{
    use ResponseTrait;

    /**
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException($this->respondWithValidationError(context: $validator->errors()));
    }
}
