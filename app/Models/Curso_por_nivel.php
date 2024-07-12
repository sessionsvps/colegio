<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso_por_nivel extends Model
{
    use HasFactory;

    protected $table = 'curso_por_niveles';
    public $incrementing = false;
    protected $primaryKey = ['codigo_curso', 'id_nivel'];
    public $timestamps = false;

    protected $guarded = [];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'codigo_curso', 'codigo_curso');
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'id_nivel', 'id_nivel');
    }
}
