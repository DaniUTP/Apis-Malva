<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleReserva extends Model
{
    use HasFactory;
    protected $table="detallereserva";
    protected $primaryKey="id_detalle_reserva";
    protected $fillable=['id_reserva','id_area','description'];
    protected $hidden=['fecha_creacion','estado'];
}
