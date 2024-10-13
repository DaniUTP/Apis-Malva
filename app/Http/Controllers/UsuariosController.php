<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\LanguageRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RecoverPasswordRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\Personal;
use App\Models\Propietario;
use App\Models\Usuarios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsuariosController extends Controller
{
    public function create(CreateRequest $request)
    {
        $language = $request->query('lang');
        try {
            $usuario = Usuarios::firstOrNew(['email' => $request->email]);
            if ($usuario->dni) {
                return CustomResponse::responseMessage('userExist', 409, $language);
            }
            $usuario->dni = $request->dni;
            $usuario->nombre = $request->nombre;
            $usuario->email = $request->email;
            $usuario->password = Hash::make($request->password);
            $usuario->id_rol = $request->id_rol;
            $usuario->save();

            return CustomResponse::responseMessage('saved', 200, $language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }

    public function login(LoginRequest $request)
    {
        $language = $request->query('lang');
        try {
            $user = Usuarios::firstWhere('email', $request->email);

            if (!$user) {
                return CustomResponse::responseMessage('notExist', 400, $language);
            }

            if($user->estado==0){
                return CustomResponse::responseMessage('notActive',403,$language);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return CustomResponse::responseMessage('badCredentials', 401, $language);
            }

            $expiredAt = Carbon::now(env('LOCATION'))->addHours(24);

            $token = $user->createToken(env('APP_NAME'), [], $expiredAt)->plainTextToken;

            return CustomResponse::responseData(['token' => $token], 200);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }

    public function me(LanguageRequest $request)
    {
        $language = $request->query('lang');
        try {
            $user = auth('sanctum')->user();
            return CustomResponse::responseData($user, 200);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }

    public function update(UpdateRequest $request)
    {
        $language = $request->query('lang');
        try {
            $usuario = Usuarios::find($request->dni);
            if (!$usuario) {
                return CustomResponse::responseMessage('userNotExist', 400, $language);
            }
            $usuario->nombre = $request->nombre;
            $usuario->id_rol = $request->id_rol;
            $usuario->save();
            return CustomResponse::responseMessage('updated',200,$language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }

    public function recoverPassword(RecoverPasswordRequest $request){
            $language=$request->query("lang");
        try {
            $usuario=Usuarios::find($request->dni);
            if(!$usuario){
                return CustomResponse::responseMessage('notExist',400,$language);
            }
            $usuario->password=Hash::make($request->new_password);
            $usuario->save();
            return CustomResponse::responseMessage('recoverPassword',200,$language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language); 
        }
    }
    public function offUser(LanguageRequest $request){
            $language=$request->query('lang');
        try {
             $user=auth('sanctum')->user();
             $offUser=Usuarios::find($user->id_usuario);
             $offUser->estado=0;
             $offUser->save();
             return CustomResponse::responseMessage('offUser',200,$language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
}
