<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;
    protected $primaryKey = ['codigo_estudiante', 'año_escolar', 'user_id', 'id_bimestre'];
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    public function boletaDeNotas()
    {
        return $this->belongsTo(Boleta_de_nota::class, ['codigo_estudiante', 'año_escolar', 'user_id'], ['codigo_estudiante', 'año_escolar', 'user_id']);
    }

    public function bimestre()
    {
        return $this->belongsTo(Bimestre::class, 'id_bimestre');
    }
}
