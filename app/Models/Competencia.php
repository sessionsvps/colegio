<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competencia extends Model
{
    use HasFactory;
    protected $table = 'competencias';
    public $incrementing = false;
    protected $primaryKey = ['codigo_curso', 'orden'];
    public $timestamps = false;

    protected $guarded = [];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'codigo_curso', 'codigo_curso');
    }
}
