<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;
    protected $table="personal";
    protected $primaryKey="dni";
    protected $fillable=['dni','id_rol','nombre','foto','fecha_creacion','estado'];
    protected $hidden=[];
    public $timestamps=false;
}
