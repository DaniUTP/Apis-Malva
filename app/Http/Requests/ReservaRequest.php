<?php

namespace App\Http\Requests;

use App\CustomResponse\CustomResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Crypt;

class ReservaRequest extends FormRequest
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
            'id_area'=>'required|numeric',
            'fecha_reserva'=>'required|date',
            'hora_inicio'=>'required|date_format:H:i',
            'hora_fin'=>'required|date_format:H:i',
            'descripcion'=>'required|string|max:100'
        ];
    }

    public function messages(){
        $language=$this->query('lang');
        return [
            'required'=>CustomResponse::responseValidation('required',$language),
            'numeric'=>CustomResponse::responseValidation('numeric',$language),
            'digits'=>CustomResponse::responseValidation('digits',$language),
            'string'=>CustomResponse::responseValidation('string',$language),
            'date'=>CustomResponse::responseValidation('date',$language),
            'hour'=>CustomResponse::responseValidation('hour',$language),
            'max'=>CustomResponse::responseValidation('max',$language)
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
