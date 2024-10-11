<?php

namespace App\Http\Controllers;

use App\customResponse\customResponse;
use App\Http\Requests\CreateRequest;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsuariosController extends Controller
{
    public function create(CreateRequest $request)
    {
        $language = $request->query('lang');
        try {
            $usuario = new Usuarios();
            $usuario->nombre = $request->nombre;
            $usuario->dni = $request->dni;
            $usuario->email = $request->email;
            $usuario->contrasena = Hash::make($request->contrasena);
            $usuario->id_rol = $request->id_rol;
            $usuario->save();

            return customResponse::responseMessage('saved', $language);
        } catch (\Throwable $th) {
            Log::info("Error: ".$th->getMessage());
            return customResponse::responseMessage('internalError',$language);
        }
    }
}
