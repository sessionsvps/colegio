<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competencia extends Model
{
    use HasFactory;
    protected $primaryKey = ['codigo_curso', 'orden'];
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'codigo_curso', 'codigo_curso');
    }

    public function nota()
    {
        return $this->hasOne(Notas_por_competencia::class, ['codigo_curso', 'orden'], ['codigo_curso', 'orden']);
    }
}
