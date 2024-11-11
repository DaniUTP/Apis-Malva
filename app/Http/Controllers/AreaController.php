<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use App\Http\Requests\CreateAreaRequest;
use App\Http\Requests\LanguageRequest;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AreaController extends Controller
{
    public function listArea(LanguageRequest $request)
    { 
        $language = $request->query('lang');
        $encript=$request->query('encr');
        try {
            $area=Area::all(['id_area','nombre_area']);
            return CustomResponse::responseData($area,200,$encript);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
    public function create(CreateAreaRequest $request){
        $language=$request->query('lang');
        try {
            $area=new Area();
            $area->nombre_area=$request->nombre_area;
            $area->id_edificio=$request->id_edificio;
            $area->aforo_maximo=$request->aforo_maximo;
            $area->save();
            return CustomResponse::responseMessage('savedArea',200,$language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
}
