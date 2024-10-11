<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Usuarios extends Model
{
    use HasFactory,HasApiTokens;
    protected $table="usuarios";
    protected $primaryKey="id_usuario";
    protected $fillable=['id_rol','nombre','dni','email'];
    protected $hidden=['contraseña','fecha_creacion','estado'];
    public $timestamps=false;
}
