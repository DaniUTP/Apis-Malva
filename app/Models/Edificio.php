<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edificio extends Model
{
    use HasFactory;
    protected $table="edificio";
    protected $primaryKey="id_edificio";
    protected $fillable=['nombre','direccion'];
    protected $hidden=['fecha_creacion','estado'];
}
