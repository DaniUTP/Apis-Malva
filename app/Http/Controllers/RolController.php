<?php

namespace App\Http\Controllers;

use App\customResponse\customResponse;
use App\Http\Requests\LanguageRequest;
use App\Http\Requests\RolRequest;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RolController extends Controller
{
    public function createRol(RolRequest $request)
    {
        $language = $request->query('lang');
        try {
            $rol = new Rol();
            $rol->nombre = $request->nombre;
            $rol->save();
            return customResponse::responseMessage('saved', $language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return customResponse::responseMessage('internalError', $language);
        }
    }

    public function listRol(LanguageRequest $request)
    {
        $language = $request->query('lang');
        try {
            $rol = Rol::all(['id_rol', 'nombre']);
            return customResponse::responseData($rol);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return customResponse::responseMessage('internalError', $language);
        }
    }
}
