<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicios extends Model
{
    protected $table='servicios';
    protected $primaryKey='id_servicio';
    protected $fillabvle=['id_tipo_servicio','nombre','descripcion','estado'];
    protected $hidden=[];
    public $timestamps=false;
    
}
