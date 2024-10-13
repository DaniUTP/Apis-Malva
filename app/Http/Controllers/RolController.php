<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
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
            $rol = Rol::firstOrNew(['nombre' => $request->nombre]);
            if ($rol->id_rol) {
                return CustomResponse::responseMessage('existRol', 409, $language);
            }
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
        try {
            $rol = Rol::all(['id_rol', 'nombre']);
            return CustomResponse::responseData($rol, 200);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }

    public function changeStatus(Request $request){
        $language=$request->query('lang');
        try {
            $offRol=Rol::find($request->id_rol);
            $offRol->estado=$request->estado;
            $offRol->save();
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
}
