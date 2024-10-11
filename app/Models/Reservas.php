<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservas extends Model
{
    use HasFactory;
    protected $table="reservas";
    protected $primaryKey="id_reserva";
    protected $fillable=['id_propietario','id_area','fecha_reserva','hora_inicio','hora_fin'];
    protected $hidden=['fecha_creacion','estado'];
}
