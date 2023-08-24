<?php

namespace App\Http\Requests;

use App\Support\Abstracts\ApiRequestConfig;
use Illuminate\Foundation\Http\FormRequest;

class LoginApiRequest extends ApiRequestConfig
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'oAuthToken' => ['required', 'string', 'min:3']
        ];
    }
}
