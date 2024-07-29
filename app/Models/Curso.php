<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory, Compoships;

    protected $primaryKey = 'codigo_curso';
    public $incrementing = false;

    # ESTOS CAMPOS NO PUEDEN SER ASIGNADOS EN MASA
    protected $guarded = ['codigo_curso'];

    // Relacion muchos a muchos
    public function niveles(){
        return $this->belongsToMany(Nivel::class, 'curso_por_niveles', 'codigo_curso', 'id_nivel');
    }

    public function catedras()
    {
        return $this->hasMany(Catedra::class, 'codigo_curso', 'codigo_curso');
    }

    // Relación muchos a muchos con Estudiante
    public function estudiantes()
    {
        return $this->belongsToMany(Estudiante::class, 'exoneraciones', ['codigo_curso'], ['codigo_estudiante', 'user_id'])
        ->withPivot('año_escolar');
    }

    public function competencias()
    {
        return $this->hasMany(Competencia::class, 'codigo_curso', 'codigo_curso');
    }

    public function getCompetencias()
    {
        return $this->competencias()->orderBy('orden')->get();
    }
}
