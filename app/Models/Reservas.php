<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservas extends Model
{
    use HasFactory;
    protected $table="reservas";
    protected $primaryKey="id_reserva";
    protected $fillable=['dni','id_area','fecha_reserva','hora_inicio','hora_fin','fecha_creacion','estado'];
    protected $hidden=[];
    public $timestamps=false;
    public function area(){
       return  $this->belongsTo(Area::class,'id_area');
    }
}
