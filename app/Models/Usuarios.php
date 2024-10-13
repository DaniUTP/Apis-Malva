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
    protected $primaryKey="dni";
    protected $fillable=['dni','id_rol','nombre','email','estado','password'];
    protected $hidden=['fecha_creacion'];
    public $timestamps=false;
}