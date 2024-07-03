<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor_Seccion extends Model
{
    protected $primaryKey = ['año_escolar', 'id_seccion', 'id_grado', 'id_nivel'];
    public $incrementing = false;

    use HasFactory;

    protected $guarded = [];
}
