<?php

namespace App\Http\Requests;

use App\customResponse\customResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateRequest extends FormRequest
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
            'lang'=>'alpha',
            'nombre'=>'required|string|max:100',
            'dni'=>'required|numeric|digits:8',
            'email'=>'required|email|max:100',
            'contrasena'=>'required|string|min:8|max:255',
            'id_rol'=>'required|numeric'
        ];
    }

    public function messages()
    {
         $language=$this->query('lang');
        return[
            'alpha'=>customResponse::responseValidation('alpha',$language),
            'required'=>customResponse::responseValidation('required',$language),
            'string'=>customResponse::responseValidation('string',$language),
            'numeric'=>customResponse::responseValidation('numeric',$language),
            'max'=>customResponse::responseValidation('max',$language),
            'digits'=>customResponse::responseValidation('digits',$language),
            'email'=>customResponse::responseValidation('email',$language),
            ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json(
            [
              'Mensaje'=>'Error',
              'error'=>$validator->errors()  
            ],400));
 }
}
