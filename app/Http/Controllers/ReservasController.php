<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use App\Http\Requests\LanguageRequest;
use App\Http\Requests\ReservaHorariosRequest;
use App\Http\Requests\ReservaRequest;
use App\Models\DetalleReserva;
use App\Models\Reservas;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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
            $usuario=auth('sanctum')->user();
            $reserva=new Reservas();
            $reserva->dni=$usuario->dni;
            $reserva->id_area=$request->id_area;
            $reserva->fecha_reserva=$request->fecha_reserva;
            $reserva->hora_inicio=$request->hora_inicio;
            $reserva->hora_fin=$request->hora_fin;
            $reserva->save();
            $detalleReserva=new DetalleReserva();
            $detalleReserva->id_reserva=$reserva->id_reserva;
            $detalleReserva->descripcion=$request->descripcion;
            $detalleReserva->save();
            return CustomResponse::responseMessage('savedReserva',200,$language);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language); 
        }

    }

    public function listHorariosDisponibles(ReservaHorariosRequest $request){
            $language=$request->query('lang');
        try {
            $horariosDisponibles = [];
            $horaInicio = new DateTime('08:00');
            $horaFin = new DateTime('22:00');
            
            while ($horaInicio < $horaFin) {
                $horaBloqueInicio = $horaInicio->format('H:i');
                $horaInicio->modify('+2 hours'); 
                $horaBloqueFin = $horaInicio->format('H:i');
                
                $horariosDisponibles[] = $horaBloqueInicio . '-' . $horaBloqueFin;
            }
            
            $reservasUsadas = Reservas::where(['id_area' => $request->id_area, 'fecha_reserva' => $request->fecha_reserva,'estado'=>1])
                ->distinct()
                ->get(['hora_inicio', 'hora_fin']);
            
            $horariosReservados = [];
            foreach ($reservasUsadas as $reserva) {
                $horaInicioReserva = new DateTime($reserva->hora_inicio);
                $horaFinReserva = new DateTime($reserva->hora_fin);
                
                while ($horaInicioReserva < $horaFinReserva) {
                    $horaBloqueReservaInicio = $horaInicioReserva->format('H:i');
                    $horaInicioReserva->modify('+2 hours');  
                    $horaBloqueReservaFin = $horaInicioReserva->format('H:i');
                    $horariosReservados[] = $horaBloqueReservaInicio . '-' . $horaBloqueReservaFin;
                }
            }
            
            $horariosDisponiblesFiltrados = array_diff($horariosDisponibles, $horariosReservados);
            $horariosDisponiblesFiltrados = array_values($horariosDisponiblesFiltrados);
            return CustomResponse::responseData($horariosDisponiblesFiltrados,200);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language); 
        }
    }
}
