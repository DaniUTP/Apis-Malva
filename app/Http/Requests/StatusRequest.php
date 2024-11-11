<?php

namespace App\Http\Requests;

use App\CustomResponse\CustomResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Crypt;

class StatusRequest extends FormRequest
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
            'dni'=>'required|integer'
        ];
    }
    public function messages(){
        $language=$this->query('lang');
        return[
            'required'=>CustomResponse::responseValidation('required',$language),
            'integer'=>CustomResponse::responseValidation('integer',$language)
        ];
    }
    public function prepareForValidation(){
        try {
            $data=json_decode(Crypt::encryptString($this->getContent()),true);
        } catch (\Throwable $th) {
            $data=json_decode($this->getContent(),true);
        }
         $this->merge($data);
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
