<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use App\Http\Requests\LanguageRequest;
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
            $personal = Personal::all(['dni', 'nombre', 'email', 'id_rol',DB::raw('DATE_FORMAT(fecha_creacion,"%d/%m/%Y") AS fecha_creacion')]);
            return CustomResponse::responseData($personal, 200);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
    public function create(){
        
    }

}
