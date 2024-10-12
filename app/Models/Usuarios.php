<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuarios extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table="usuarios";
    protected $primaryKey="id_usuario";
    protected $fillable=['id_rol','nombre','email'];
    protected $hidden=['password','fecha_creacion','estado'];
    public $timestamps=false;
}