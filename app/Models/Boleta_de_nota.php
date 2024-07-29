<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boleta_de_nota extends Model
{
    use HasFactory;
    protected $primaryKey = ['codigo_estudiante', 'año_escolar','user_id'];
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    public function institucion()
    {
        return $this->belongsTo(Institucion::class, 'codigo_modular', 'codigo_modular');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, ['codigo_estudiante', 'año_escolar', 'user_id'], ['codigo_estudiante', 'año_escolar', 'user_id']);
    }

    public function notasPorCompetencias()
    {
        return $this->hasMany(Notas_por_competencia::class, ['codigo_estudiante', 'año_escolar', 'user_id'], ['codigo_estudiante', 'año_escolar', 'user_id']);
    }
}
