<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    use HasFactory;
    protected $table = 'instituciones';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = ['codigo_modular'];
    protected $guarded = [];

    public function boletasDeNotas()
    {
        return $this->hasMany(Boleta_de_nota::class, 'codigo_modular', 'codigo_modular');
    }

}
