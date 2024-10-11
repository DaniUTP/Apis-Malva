<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleIncidenciaArea extends Model
{
    use HasFactory;
    protected $table="detalleincidenciaarea";
    protected $primaryKey="id_detalle_incidencia_area";
    protected $fillable=['id_area','id_incidencia','descripcion','fecha'];
    protected $hidden=['fecha_creacion','estado'];

}
