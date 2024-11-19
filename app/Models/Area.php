<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $table="area";
    protected $primaryKey="id_area";
    protected $fillable=['id_edificio','nombre_area'];
    protected $hidden=['fecha_creacion','estado'];
    public function reservas()
    {
        return $this->hasMany(Reservas::class,'id_area');
    }
}
