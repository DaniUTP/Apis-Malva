<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\LoginRequest;
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
            $usuario=Usuarios::firstOrNew(['email'=>$request->email]);
            if($usuario->nombre){
                return CustomResponse::responseMessage('userExist',400,$language);
            }
            $usuario->nombre = $request->nombre;
            $usuario->email = $request->email;
            $usuario->password = Hash::make($request->password);
            $usuario->id_rol = $request->id_rol;
            $usuario->save();
            return CustomResponse::responseMessage('saved', 200,$language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError',500, $language);
        }
    }

    public function login(LoginRequest $request)
    {
        $language = $request->query('lang');
        try {
            $user = Usuarios::firstWhere('email', $request->email);

            if (!$user) {
                return CustomResponse::responseMessage('notExist',400, $language);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return CustomResponse::responseMessage('badCredentials',400, $language);
            }

            $expiredAt = Carbon::now(env('LOCATION'))->addHours(24);

            $token = $user->createToken(env('APP_NAME'), [], $expiredAt)->plainTextToken;

            return CustomResponse::responseData(['token' => $token],200);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError',500, $language);
        }
    }

    public function me(Request $request){
            $language=$request->query('lang');
        try {
            $user=auth('sanctum')->user();
            return CustomResponse::responseData($user,200);
        } catch (\Throwable $th) {
            return CustomResponse::responseMessage('internalError',500, $language);
        }

    }
}
