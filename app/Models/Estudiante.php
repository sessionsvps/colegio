<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Estudiante extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = ['codigo_estudiante', 'user_id'];


    # ESTOS CAMPOS NO PUEDEN SER ASIGNADOS EN MASA
    protected $guarded = [];

    protected $dates = ['fecha_nacimiento'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFechaNacimientoAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function secciones()
    {
        return $this->belongsToMany(Seccion::class, 'estudiante_secciones', ['codigo_estudiante', 'user_id'], ['id_seccion', 'id_grado', 'id_nivel'])
        ->withPivot('año_escolar');
    }

    // Relación muchos a muchos con Curso
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'exoneraciones', ['codigo_estudiante', 'user_id'], ['codigo_curso'])
        ->withPivot('año_escolar');
    }

}
