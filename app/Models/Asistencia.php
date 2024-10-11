<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;
    protected $table="asistencia";
    protected $primaryKey="id_asistencia";
    protected $fillable=['id_usuario','fecha','hora_entrada','hora_salida'];
    protected $hidden=['fecha_creacion','estado'];
}
