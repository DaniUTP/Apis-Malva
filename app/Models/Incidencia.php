<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    use HasFactory;
    protected $table="incidencia";
    protected $primaryKey="id_incidencia";
    protected $fillable=['descripcion','fecha'];
    protected $hidden=['fecha_creacion','estado'];
}
