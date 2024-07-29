<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Thiagoprz\CompositeKey\HasCompositeKey;

class Docente extends Model
{
    use HasFactory,HasCompositeKey,Compoships;
    
    protected $primaryKey = ['codigo_docente', 'user_id'];
    public $incrementing = false;

    # ESTOS CAMPOS NO PUEDEN SER ASIGNADOS EN MASA
    protected $guarded = [];

    public function getKeyName()
    {
        return ['codigo_docente', 'user_id'];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function estado_civil()
    {
        return $this->belongsTo(Estado::class, 'id_estado');
    }

    public function catedras()
    {
        return $this->hasMany(Catedra::class, ['codigo_docente', 'user_id'], ['codigo_docente', 'user_id']);
    }

    public function secciones()
    {
        return $this->belongsToMany(Seccion::class, 'tutor_secciones', ['codigo_docente', 'user_id'], ['id_seccion', 'id_grado', 'id_nivel'])
            ->withPivot('año_escolar');
    }

    public function getFechaNacimientoAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getFechaIngresoAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

}
