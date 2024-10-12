<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use App\Models\Propietario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PropietarioController extends Controller
{
    public function create(Request $request)
    {
        $language = $request->query('lang');
        try {
            
            $propietario = new Propietario();
            $propietario->id_usuario = $request->id_usuario;
            $propietario->nombre = $request->nombre;
            $propietario->dni = $request->dni;
            $propietario->email = $request->email;
            $propietario->save();

            return CustomResponse::responseMessage('saved', 200, $language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
}
