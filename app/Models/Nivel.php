<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{

    protected $primaryKey = 'id_nivel';
    public $incrementing = true;

    use HasFactory;

    protected $fillable = [];


    // Relacion de uno a muchos
    public function grados()
    {
        return $this->hasMany(Grado::class, 'id_nivel', 'id_nivel');
    }

    // Relacion muchos a muchos
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'curso_por_niveles', 'id_nivel', 'codigo_curso');
    }


    
}
