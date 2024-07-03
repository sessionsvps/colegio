<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;

    public $incrementing = false;
    protected $primaryKey = ['codigo_docente', 'user_id'];

    # ESTOS CAMPOS NO PUEDEN SER ASIGNADOS EN MASA
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function estado_civil()
    {
        return $this->belongsTo(Estado::class);
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


}
