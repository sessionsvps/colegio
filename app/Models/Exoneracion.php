<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class Exoneracion extends Model
{
    use HasFactory, Compoships, HasCompositeKey;
    
    protected $table = 'exoneraciones';
    protected $primaryKey = ['codigo_estudiante', 'codigo_curso', 'año_escolar', 'user_id'];
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = [];

    public function getKeyName()
    {
        return ['codigo_estudiante', 'codigo_curso', 'año_escolar', 'user_id'];
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'codigo_curso', 'codigo_curso');
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante_Seccion::class, ['codigo_estudiante', 'año_escolar', 'user_id'], ['codigo_estudiante', 'año_escolar', 'user_id']);
    }
}
