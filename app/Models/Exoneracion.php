<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exoneracion extends Model
{
    use HasFactory, Compoships;
    
    protected $table = 'exoneraciones';
    protected $primaryKey = ['codigo_estudiante', 'codigo_curso', 'año_escolar', 'user_id'];
    public $incrementing = false;    

    protected $guarded = [];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'codigo_curso', 'codigo_curso');
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante_Seccion::class, ['codigo_estudiante', 'año_escolar', 'user_id'], ['codigo_estudiante', 'año_escolar', 'user_id']);
    }
}
