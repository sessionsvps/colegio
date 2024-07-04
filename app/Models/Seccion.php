<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;

    protected $table = 'secciones';
    protected $primaryKey = ['id_seccion','id_grado', 'id_nivel'];
    public $incrementing = false;

    use HasFactory;

    protected $fillable = [];

    // Relacion de uno a muchos
    public function grado()
    {
        return $this->belongsTo(Grado::class, ['id_grado', 'id_nivel'], ['id_grado', 'id_nivel']);
    }

    public function catedras()
    {
        return $this->hasMany(Catedra::class, ['id_seccion', 'id_nivel','id_grado'], ['id_seccion', 'id_nivel','id_grado']);
    }

    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'tutor_secciones', ['id_seccion', 'id_grado', 'id_nivel'], ['codigo_docente', 'user_id'])
        ->withPivot('año_escolar');
    }

    public function estudiantes()
    {
        return $this->belongsToMany(Estudiante::class, 'estudiante_secciones', ['id_seccion', 'id_grado', 'id_nivel'], ['codigo_estudiante', 'user_id'])
        ->withPivot('año_escolar');
    }
}
