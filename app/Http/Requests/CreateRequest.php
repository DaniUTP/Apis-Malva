<?php

namespace App\Http\Requests;

use App\CustomResponse\CustomResponse;
use App\Models\Usuarios;
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
            'dni'=>'required|numeric',
            'email'=>'required|email|max:100',
            'password'=>'required|string|min:8|max:255',
            'id_rol'=>'required|numeric'
        ];
    }

    public function messages()
    {
         $language=$this->query('lang');
        return[
            'alpha'=>CustomResponse::responseValidation('alpha',$language),
            'required'=>CustomResponse::responseValidation('required',$language),
            'string'=>CustomResponse::responseValidation('string',$language),
            'numeric'=>CustomResponse::responseValidation('numeric',$language),
            'max'=>CustomResponse::responseValidation('max',$language),
            'digits'=>CustomResponse::responseValidation('digits',$language),
            'email'=>CustomResponse::responseValidation('email',$language),
            ];
    }

    public function passedValidation(){
        $language=$this->query('lang');
        $usuario = Usuarios::firstWhere('email',$this->email);
        if ($usuario) {
            return throw new HttpResponseException(CustomResponse::responseMessage('userExist', 409, $language));
        }
    }
    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json(
            [
              'Mensaje'=>'Error',
              'error'=>$validator->errors()  
            ],400));
        }
}
