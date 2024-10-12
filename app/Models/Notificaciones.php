<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificaciones extends Model
{
    use HasFactory;
    protected $table="notificaciones";
    protected $primaryKey="id_notificacion";
    protected $fillable=['id_incidencia','id_personal','fecha_notificacion','mensaje'];
    protected $hidden=['fecha_creacion','estado'];
}