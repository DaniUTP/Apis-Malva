<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use App\Models\Propietario;
use App\Models\Usuarios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();
        dd($user);
    }
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
    
    public function handleFacebookCallback(Request $request)
{
    // Manejo del estado (opcional, dependiendo de tu implementación)
    $state = $request->input('state');

    try {
        // Obtén el usuario de Facebook
        $user = Socialite::driver('facebook')->user();

        // El accessToken también se puede obtener desde el objeto de usuario
        $accessToken = $user->token;

        // Aquí puedes manejar el usuario y el accessToken según sea necesario
        // Por ejemplo, guardarlo en la base de datos, iniciar sesión al usuario, etc.

        // Para depurar, puedes imprimir el accessToken
        dd($accessToken); // Muestra el accessToken en la pantalla
    } catch (Throwable $e) {
        // Manejo de errores en caso de que falle la obtención del usuario
        return response()->json(['error' => 'Error al intentar obtener el usuario de Facebook: ' . $e->getMessage()], 500);
    }
}
public function social(Request $request){
    $encript=$request->query('encr');
    $language=$request->query('lang');
try {
    $driver=$request->driver;
    $accessToken=$request->accessToken;
    $userData=Socialite::driver($driver)->userFromToken($accessToken);
    $user=Usuarios::firstOrNew(['email'=>$userData['email']]);
    $expired_at=Carbon::now()->addHours(24);
    if($user->name && $user->id_rol==2){
        $token=$user->createToken(env('APP_NAME'),[],$expired_at)->plainTextToken;
        return CustomResponse::responseSocial('pass',$token,200,$encript);
    }
    if($user->id_rol==2){
        $collection=collect([1,2,3,4,5,6,7,8]);
        $propietario = new Propietario();
        $propietario->dni=$collection->random(8)->join('');
        $propietario->nombre =$userData['name']==null?$userData['nickname']:$userData['name'];
        $propietario->foto = $userData['avatar'];
        $propietario->save();
        return CustomResponse::responseSocial('created',$propietario->dni,200,$encript);
    }
    return CustomResponse::responseMessage('onlyOwner',400,$language);
} catch (\Throwable $th) {
    return response()->json(['error' =>$th->getMessage()], 500);
}
}
}
