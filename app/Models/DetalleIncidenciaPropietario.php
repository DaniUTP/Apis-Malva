<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleIncidenciaPropietario extends Model
{
    use HasFactory;
    protected $table="detalleincidenciapropietario";
    protected $primaryKey="	id_detalle_incidencia_propietario";
    protected $fillable=['id_propietario','id_incidencia','description','fecha'];
    protected $hidden=['fecha_creacion','estado'];
}
