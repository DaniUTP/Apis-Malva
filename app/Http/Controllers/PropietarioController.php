<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use App\Http\Requests\PropietarioRequest;
use App\Models\Propietario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PropietarioController extends Controller
{
    public function create(PropietarioRequest $request)
    {
        $language = $request->query('lang');
        try {
            $propietario = Propietario::firstOrNew(['dni' => $request->dni]);
            if ($propietario->nombre) {
                return CustomResponse::responseMessage('existPropietario', 409, $language);
            }
            $propietario->nombre = $request->nombre;
            $propietario->fecha_nacimiento = $request->fecha_nacimiento;
            $propietario->save();
            return CustomResponse::responseMessage('saved', 200, $language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
}
