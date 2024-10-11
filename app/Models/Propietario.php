<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    use HasFactory;
    protected $table="propietario";
    protected $primarykey="id_propietario";
    protected $fillable=['nombre','email'];
    protected $hidden=['fecha_creacion','estado'];
}
