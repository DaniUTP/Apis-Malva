<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use App\Http\Requests\LanguageRequest;
use App\Http\Requests\RolRequest;
use App\Http\Requests\StatusRolRequest;
use App\Models\Rol;
use Illuminate\Support\Facades\Log;

class RolController extends Controller
{
    public function createRol(RolRequest $request)
    {
        $language = $request->query('lang');
        try {
            $rol = new Rol();
            $rol->nombre = $request->nombre;
            $rol->id_rol = $request->id_rol;
            $rol->save();
            return CustomResponse::responseMessage('saved', 200, $language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }

    public function listRol(LanguageRequest $request)
    {
        $language = $request->query('lang');
        $encript = $request->query('encr');
        try {
            $rol = Rol::all(['id_rol', 'nombre']);
            return CustomResponse::responseData($rol, 200, $encript);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
    public function findRol(LanguageRequest $request)
    {
        $language = $request->query('lang');
        $encript = $request->query('encr');
        try {
            $rol=Rol::find($request->id);
            return CustomResponse::responseData($rol,200,$encript);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }

    public function changeStatus(StatusRolRequest $request)
    {
        $language = $request->query('lang');
        try {
            $statusRol = Rol::find($request->id_rol);
            $statusRol->estado = $request->estado;
            $statusRol->save();
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
}
