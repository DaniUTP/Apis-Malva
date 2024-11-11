<?php

namespace App\Http\Requests;

use App\CustomResponse\CustomResponse;
use App\Models\Usuarios;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class LoginRequest extends FormRequest
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
            'email'=>'required|email',
            'password'=>'required|string'
        ];
    }

    public function messages()
    {
            $language=$this->query('lang');
        return[
            'required'=>CustomResponse::responseValidation('required',$language),
            'email'=>CustomResponse::responseValidation('email',$language),
            'string'=>CustomResponse::responseValidation('string',$language)
        ];
    }
    public function prepareForValidation() {
        try {
            try {
                $data = json_decode(Crypt::decryptString($this->getContent()), true);
            } catch (\Exception $e) {
                $data = json_decode($this->getContent(), true);
            }
            $this->merge($data);
    
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
    
    public function passedValidation(){
        $language=$this->query('lang');
        $user = Usuarios::firstWhere('email', $this->email);
        if (!$user) {
            return throw new HttpResponseException(CustomResponse::responseMessage('notExist', 400, $language));
        }

        if($user->estado==0){
            return throw new HttpResponseException(CustomResponse::responseMessage('notActive',403,$language));
        }

        if (!Auth::attempt($this->only(['email', 'password']))) {
            return throw new HttpResponseException(CustomResponse::responseMessage('badCredentials', 401, $language));
        }
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
