<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use App\Http\Requests\LanguageRequest;
use App\Http\Requests\ReservaRequest;
use App\Models\Reservas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ReservasController extends Controller
{
    public function listReservas(LanguageRequest $request){
            $language=$request->query('lang');
        try {
            $usuario = auth('sanctum')->user();
            $where=[];
            if(!$usuario->id_rol=1){
              $where=['dni'=>$usuario->dni];  
            }
            $reservas=Reservas::where($where)
                     ->where(DB::raw('CONCAT(fecha_reserva," ",hora_fin)'),'>',DB::raw('NOW()'))
                    ->get(['id_reserva',DB::raw('DATE_FORMAT(fecha_reserva,"%d/%m/%Y") AS fecha_reserva'),'hora_inicio','hora_fin',DB::raw('DATE_FORMAT(fecha_creacion,"%d/%m/%Y") AS fecha_creacion'),'estado']);
            return CustomResponse::responseData($reservas,200);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language); 
        }
    }

    public function create(ReservaRequest $request){
        $language=$request->query('lang');
        try {
            $reserva=new Reservas();
            $reserva->dni=$request->dni;
            $reserva->id_area=$request->id_area;
            $reserva->fecha_reserva=$request->fecha_reserva;
            $reserva->hora_inicio=$request->hora_inicio;
            $reserva->hora_fin=$request->hora_fin;
            $reserva->save();
            return CustomResponse::responseMessage('savedReserva',200,$language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language); 
        }

    }

    public function listHorariosUsados(LanguageRequest $request){
            $language=$request->query('lang');
        try {
            $reservasUsadas=Reservas::distinct()->get(['fecha_reserva']);
            foreach($reservasUsadas as $value){
                $value->horas=Reservas::where('fecha_reserva',$value->fecha_reserva)->get(['hora_inicio','hora_fin']);
            }
            return CustomResponse::responseData($reservasUsadas,200);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language); 
        }
    }
}
