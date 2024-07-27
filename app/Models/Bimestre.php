<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bimestre extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = ['id'];

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_bimestre');
    }

    public function notasPorCompetencias()
    {
        return $this->hasMany(Notas_por_competencia::class, 'id_bimestre');
    }
}
