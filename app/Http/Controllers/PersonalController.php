<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use App\Http\Requests\LanguageRequest;
use App\Http\Requests\PersonalRequest;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersonalController extends Controller
{
    public function listPersonal(LanguageRequest $request)
    {
        $language = $request->query('lang');
        try {
            $personal = Personal::all(['dni', 'nombre', 'email', 'id_rol', DB::raw('DATE_FORMAT(fecha_creacion,"%d/%m/%Y") AS fecha_creacion'),'estado']);
            return CustomResponse::responseData($personal, 200);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
    public function create(PersonalRequest $request)
    {
        $language = $request->query('lang');
        try {

            $personal = Personal::firstOrNew(['dni' => $request->dni]);
            if ($personal->nombre) {
                return CustomResponse::responseMessage('existPersonal', 409, $language);
            }
            $personal->nombre = $request->nombre;
            $personal->email = $request->email;
            $personal->id_rol = $request->id_rol;
            $personal->save();
            return CustomResponse::responseMessage('createdPersonal',200,$language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
    public function update(Request $request){
        $language = $request->query('lang');
        try {
            $personal=Personal::find($request->dni);
            if(!$personal){
                return CustomResponse::responseMessage('notExistPersonal',400,$language);
            }
            $personal->nombre=$request->nombre;
            $personal->email=$request->email;
            $personal->foto=$request->foto;
            $personal->id_rol=$request->id_rol;
            $personal->save();
            return CustomResponse::responseMessage('updated',200,$language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
}
