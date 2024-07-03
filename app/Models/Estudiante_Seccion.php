<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante_Seccion extends Model
{
    protected $primaryKey = ['codigo_estudiante', 'año_escolar', 'user_id'];
    public $incrementing = false;

    use HasFactory;

    protected $guarded = [];
}
