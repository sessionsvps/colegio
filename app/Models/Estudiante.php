<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Awobaz\Compoships\Compoships;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Support\Facades\DB;

class Estudiante extends Model
{
    use HasFactory;
    Use Compoships;
    use HasCompositeKey;

    protected $primaryKey = ['codigo_estudiante', 'user_id'];
    public $incrementing = false;

    # ESTOS CAMPOS NO PUEDEN SER ASIGNADOS EN MASA
    protected $guarded = [];

    protected $dates = ['fecha_nacimiento'];

    public function getKeyName()
    {
        return ['codigo_estudiante', 'user_id'];
    }

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
        return $this->belongsToMany(
            Seccion::class,
            'estudiante_secciones',
            ['codigo_estudiante', 'user_id'],
            ['id_seccion', 'id_nivel', 'id_grado']
        )->withPivot('año_escolar');
    }

    public function estudiantes_seccion()
    {
        return $this->hasMany(Estudiante_Seccion::class, ['codigo_estudiante', 'user_id'], ['codigo_estudiante', 'user_id']);
    }

    // Relación muchos a muchos con Curso
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'exoneraciones', ['codigo_estudiante', 'user_id'], ['codigo_curso'])
        ->withPivot('año_escolar');
    }
    // Obtener id_nivel desde la tabla intermedia
    public function getNivelIdAttribute()
    {
        $currentYear = Carbon::now()->year;        
        $registro = DB::table('estudiante_secciones')
        ->where('codigo_estudiante', $this->codigo_estudiante)
            ->where('user_id', $this->user_id)
            ->where('año_escolar',$currentYear)
            ->first();

        return $registro ? $registro->id_nivel : null;
    }

    public function getGradoIdAttribute()
    {
        $currentYear = Carbon::now()->year;
        $registro = DB::table('estudiante_secciones')
        ->where('codigo_estudiante', $this->codigo_estudiante)
            ->where('user_id', $this->user_id)
            ->where('año_escolar',$currentYear)
            ->first();

        return $registro ? $registro->id_grado : null;
    }

    public function getSeccionIdAttribute()
    {
        $currentYear = Carbon::now()->year;
        $registro = DB::table('estudiante_secciones')
        ->where('codigo_estudiante', $this->codigo_estudiante)
            ->where('user_id', $this->user_id)
            ->where('año_escolar',$currentYear)
            ->first();

        return $registro ? $registro->id_seccion : null;
    }

}
