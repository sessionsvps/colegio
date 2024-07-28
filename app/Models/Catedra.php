<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catedra extends Model
{

    use HasFactory, Compoships;

    protected $primaryKey = ['codigo_curso', 'id_seccion', 'id_grado', 'id_nivel'];
    public $incrementing = false;

    protected $guarded = [];

    public function docente()
    {
        return $this->belongsTo(Docente::class,['codigo_docente', 'user_id'], ['codigo_docente', 'user_id']);
    }

    public function secciones()
    {
        return $this->hasMany(Seccion::class,['id_seccion', 'id_nivel', 'id_grado'], ['id_seccion', 'id_nivel', 'id_grado']);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'codigo_curso', 'codigo_curso');
    }

}
