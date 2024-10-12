<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    use HasFactory;
    protected $table="propietario";
    protected $primarykey="dni";
    protected $fillable=['nombre','fecha_nacimiento'];
    protected $hidden=['fecha_creacion','estado'];
    public $timestamp=false;
}
