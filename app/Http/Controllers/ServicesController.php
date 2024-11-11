<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use App\Http\Requests\LanguageRequest;
use App\Models\Servicios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServicesController extends Controller
{
    public function listServicios(LanguageRequest $request)
    {
        $language = $request->query('lang');
        $encript=$request->query("encr");
        try {
            $servicios = Servicios::all();
            return CustomResponse::responseData($servicios, $language,$encript);
        } catch (\Throwable $th) {
            Log::info('Error: ' . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
}
