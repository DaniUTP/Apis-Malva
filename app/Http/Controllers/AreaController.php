<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use App\Http\Requests\LanguageRequest;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AreaController extends Controller
{
    public function listArea(LanguageRequest $request)
    { 
        $language = $request->query('lang');
        try {
            $area=Area::all(['id_area','nombre_area']);
            return CustomResponse::responseData($area,200);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
}
