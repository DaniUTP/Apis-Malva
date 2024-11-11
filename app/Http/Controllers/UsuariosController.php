<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\LanguageRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RecoverPasswordRequest;
use App\Http\Requests\StatusRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\Personal;
use App\Models\Propietario;
use App\Models\Usuarios;
use Carbon\Carbon;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;
use Illuminate\Http\Request;
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
     *             @OA\Property(property="image", type="string", example="https://res.cloudinary.com/dly4rnmgh/image/upload/v1730668603/imagenes/ckboys2cdnapznjb0zsl.png"),
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
            $usuario = new Usuarios();
            $usuario->dni = $request->dni;
            $usuario->nombre = $request->nombre;
            $usuario->email = $request->email;
            $usuario->password = Hash::make($request->password);
            $usuario->id_rol = $request->id_rol;
            $usuario->save();
            if ($request->id_rol == 1 || $request->id_rol == 3 || $request->id_rol == 4) {
                $personal = new Personal();
                $personal->dni = $request->dni;
                $personal->nombre = $request->nombre;
                $personal->foto = $request->image;
                $personal->id_rol = $request->id_rol;
                $personal->save();
            } else {
                $propietario = new Propietario();
                $propietario->dni = $request->dni;
                $propietario->nombre = $request->nombre;
                $propietario->foto = $request->image;
                $propietario->save();
            }
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
        $encript = $request->query('encr');
        try {
            $user = Usuarios::firstWhere('email', $request->email);
            Log::info('usuario'.$user);
            $expiredAt = Carbon::now(env('LOCATION'))->addHours(24);
            $token = $user->createToken(config('app.name'), ['*'], $expiredAt)->plainTextToken;
            return CustomResponse::responseData(['token' => $token], 200, $encript);
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
     *             @OA\Property(property="estado", type="number", example="1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Las credenciales son incorrectas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Se superó el límite de peticiones",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se superó el límite de peticiones")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error Interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un error, inténtelo nuevamente.")
     *         )
     *     )
     * )
     */
    public function me(LanguageRequest $request)
    {
        $language = $request->query('lang');
        $encript = $request->query('encr');
        try {
            $user = auth('sanctum')->user();
            return CustomResponse::responseData($user, 200, $encript);
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
            $usuarioLogin = auth('sanctum')->user();
            $usuario = Usuarios::find($usuarioLogin->dni);
            $usuario->nombre = $request->nombre;
            $usuario->id_rol = $request->id_rol;
            $usuario->save();
            return CustomResponse::responseMessage('updated', 200, $language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
    /**
     * @OA\Post (
     *     path="/api/v1/auth/recover",
     *     tags={"Auth"},
     *     summary= "recover password",
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
     *             required={"dni", "new_password"},
     *             @OA\Property(property="dni", type="number", example="12345678"),
     *             @OA\Property(property="new_password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Se actualizo correctamente la contraseña")
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
    public function recoverPassword(RecoverPasswordRequest $request)
    {
        $language = $request->query("lang");
        try {
            $usuario = Usuarios::find($request->dni);
            $usuario->password = Hash::make($request->new_password);
            $usuario->save();
            return CustomResponse::responseMessage('recoverPassword', 200, $language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
    public function changeStatus(StatusRequest $request)
    {
        $language = $request->query('lang');
        try {
            $foundUser = Usuarios::find($request->dni);
            $foundUser->estado = 0;
            $foundUser->save();
            $foundPersonal = Personal::find($request->dni);
            $foundPersonal->estado = 0;
            $foundPersonal->save();
            return CustomResponse::responseMessage('disabled', 200, $language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
}
