<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    use HasFactory;
    protected $table="propietario";
    protected $primarykey="dni";
    protected $fillable=['nombre','foto'];
    protected $hidden=['fecha_creacion','estado'];
    public $timestamps=false;
}
