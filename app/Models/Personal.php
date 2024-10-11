<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;
    protected $table="personal";
    protected $primaryKey="id_personal";
    protected $fillable=['id_rol','nombre','email'];
    protected $hidden=['fecha_creacion','estado'];
}
