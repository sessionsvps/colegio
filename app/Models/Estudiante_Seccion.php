<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class Estudiante_Seccion extends Model
{
    use HasFactory, Compoships, HasCompositeKey;

    protected $table = 'estudiante_secciones';
    protected $primaryKey = ['codigo_estudiante', 'año_escolar', 'user_id'];
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, ['codigo_estudiante', 'user_id'], ['codigo_estudiante', 'user_id']);
    }

    public function seccion()
    {
        return $this->belongsTo(Seccion::class, ['id_seccion', 'id_nivel', 'id_grado'], ['id_seccion', 'id_nivel', 'id_grado']);
    }

    public function exoneraciones()
    {
        return $this->hasMany(Exoneracion::class, ['codigo_estudiante','user_id'], ['codigo_estudiante','user_id']);
    }
}
