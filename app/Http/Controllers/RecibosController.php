<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use App\Http\Requests\LanguageRequest;
use App\Models\Recibos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RecibosController extends Controller
{
    public function listRecibos(LanguageRequest $request)
    {
        $language = $request->query('lang');
        try {
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
    public function create(Request $request)
    {
        $language = $request->query('lang');
        try {
            $recibo = new Recibos();
            $recibo->dni = $request->dni;
            $recibo->id_servicio = $request->id_servicio;
            $recibo->url = $request->url;
            $recibo->save();
            return CustomResponse::responseMessage('saveReceipt', 200, $language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
    public function find(Request $request)
    {
        $language = $request->query('lang');
        $encript = $request->query('encr');
        try {
            $recibo = Recibos::find($request->idre);
            return CustomResponse::responseData($recibo, 200, $encript);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
}
