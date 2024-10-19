<?php

namespace App\Http\Requests;

use App\CustomResponse\CustomResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StatusRolRequest extends FormRequest
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
           'id_rol'=>'required|integer'
        ];
    }
    public function messages(){
        $language=$this->query('lang');
        return[
            'required'=>CustomResponse::responseValidation('required',$language),
            'integer'=>CustomResponse::responseValidation('integer',$language),
        ];
    }
    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json(
            [
                'message' => 'error',
                'errors' => $validator->errors()
            ]
       , 400));
    }
}
