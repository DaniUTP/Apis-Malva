<?php

namespace App\Http\Requests;

use App\customResponse\customResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RolRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre'=>'required|string|regex:/^[a-zA-Z0-9\s\-]+$/'
        ];
    }
    public function messages(){
            $language=$this->query('lang');
    return[
        'required'=>customResponse::responseValidation('required',$language),
        'string'=>customResponse::responseValidation('string',$language),
        'regex'=>customResponse::responseValidation('regex',$language)
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            [
                'message' => 'error',
                'errors' => $validator->errors()
            ]
       , 400));
    }
}
