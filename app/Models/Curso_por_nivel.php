<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso_por_nivel extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = ['codigo_curso', 'id_nivel'];

    protected $guarded = [];

}
