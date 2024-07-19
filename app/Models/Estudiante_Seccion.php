<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante_Seccion extends Model
{

    protected $table = 'estudiante_secciones';
    protected $primaryKey = ['codigo_estudiante', 'año_escolar', 'user_id'];
    public $incrementing = false;
    public $timestamps = false;
    
    use HasFactory;
    use Compoships;

    protected $guarded = [];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, ['codigo_estudiante', 'user_id'], ['codigo_estudiante', 'user_id']);
    }

    public function seccion()
    {
        return $this->belongsTo(Seccion::class, ['id_seccion', 'id_nivel', 'id_grado'], ['id_seccion', 'id_nivel', 'id_grado']);
    }
}
