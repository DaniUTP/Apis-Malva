<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialIncidencia extends Model
{
    use HasFactory;
    protected $table="historialincidencia";
    protected $primaryKey="id_historial";
    protected $fillable=['id_incidencia','fecha_modificacion','detalles'];
    protected $hidden=['fecha_creacion','estado'];
}
