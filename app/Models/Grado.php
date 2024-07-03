<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;

    public $incrementing = false;
    protected $primaryKey = ['id_grado','id_nivel'];

    protected $fillable = [];

    // Relación de uno a muchos con Niveles
    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'id_nivel', 'id_nivel');
    }
    
    // Relación de uno a muchos con Secciones
    public function secciones()
    {
        return $this->hasMany(Seccion::class,['id_grado', 'id_nivel'], ['id_grado', 'id_nivel']);
    }
}

