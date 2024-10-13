<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleReserva extends Model
{
    use HasFactory;
    protected $table="detallereserva";
    protected $primaryKey="id_detalle_reserva";
    protected $fillable=['id_reserva','description'];
    protected $hidden=['fecha_creacion','estado'];
    public $timestamps=false;
}
