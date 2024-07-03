<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_estado';
    public $incrementing = true;

    # ESTOS CAMPOS PUEDEN SER ASIGNADOS EN MASA
    protected $fillable = [];

    # DEFINIR LAS RELACIONES CON OTROS MODELOS, SI LAS HAY
    public function docentes()
    {
        return $this->hasMany(Docente::class);
    }
}
