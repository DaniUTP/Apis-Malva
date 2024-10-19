<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\LanguageRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RecoverPasswordRequest;
use App\Http\Requests\StatusRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\Usuarios;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsuariosController extends Controller
{
        /**
     * @OA\Post (
     *     path="/api/v1/auth/create",
     *     tags={"Auth"},
     *     summary="Create an account",
     *     @OA\Parameter(
     *         name="lang",
     *         in="query",
     *         description="Idioma",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="User credentials",
     *         @OA\JsonContent(
     *             required={"nombre","dni","email","password","id_rol"},
     *             @OA\Property(property="nombre", type="string", example="Daniel"),
     *             @OA\Property(property="dni", type="number", example="74112299"),
     *             @OA\Property(property="email", type="string", example="aldanagerardo24@gmail.com"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="id_rol", type="number", example="1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se registro correctamente"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflicto por un usuario existente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El usuario ya existe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Se supero el limite de peticiones",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se supero el limite de peticiones")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error Interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrio un error,intentelo nuevamente.")
     *         )
     *     )
     * )
     */
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
    /**
     * @OA\Post (
     *     path="/api/v1/auth/login",
     *     tags={"Auth"},
     *     summary= "Login",
     *     @OA\Parameter(
     *         name="lang",
     *         in="query",
     *         description="Idioma",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="User credentials",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="aldanagerardo24@gmail.com"),
     *             @OA\Property(property="password", type="string", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|a54fds45fa4554f45adgssGADG5S4GSF54GS5FG4SFGSF")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Cuerpo de peticion incorrecta",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El usuario no existe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Las credenciales son incorrectas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Rechazo de solicitud",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El usuario no se encuentra activado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Se supero el limite de peticiones",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se supero el limite de peticiones")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error Interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrio un error,intentelo nuevamente.")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get (
     *     path="/api/v1/auth/me",
     *     tags={"Auth"},
     *     summary="Return the user information",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="lang",
     *         in="query",
     *         description="Idioma",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="dni", type="number", example="12345678"),
     *             @OA\Property(property="id_rol", type="number", example="1"),
     *             @OA\Property(property="nombre", type="string", example="Daniel"),
     *             @OA\Property(property="email", type="string", example="aldanagerardo24@gmail.com"),
     *             @OA\Property(property="estado", type="number", example="1"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales invalidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Las credenciales son incorrectas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales invalidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Las credenciales son incorrectas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Se supero el limite de peticiones",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se supero el limite de peticiones")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error Interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrio un error,intentelo nuevamente.")
     *         )
     *     )
     * )
     */
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
    /**
     * @OA\Post (
     *     path="/api/v1/auth/update",
     *     tags={"Auth"},
     *     summary= "Update account",
     *     @OA\Parameter(
     *         name="lang",
     *         in="query",
     *         description="Idioma",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="User credentials",
     *         @OA\JsonContent(
     *             required={"dni", "nombre","id_rol"},
     *             @OA\Property(property="dni", type="number", example="12345678"),
     *             @OA\Property(property="nombre", type="string", example="Pedro"),
     *             @OA\Property(property="id_rol", type="number", example="1"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El usuario se actualizo correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Cuerpo de peticion incorrecta",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El usuario no existe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Rechazo de solicitud",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El usuario no se encuentra activado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Se supero el limite de peticiones",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se supero el limite de peticiones")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error Interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrio un error,intentelo nuevamente.")
     *         )
     *     )
     * )
     */
    public function update(UpdateRequest $request)
    {
        $language = $request->query('lang');
        try {
            $usuarioLogin=auth('sanctum')->user();
            $usuario = Usuarios::find($usuarioLogin->dni);
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
    public function changeStatus(StatusRequest $request){
        $language=$request->query('lang');
        try {
            $usuario=auth('sanctum')->user();
            $foundUser=Usuarios::find($usuario->id_usuario);
            $foundUser->estado=$request->estado;
            $foundUser->save();
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
}
