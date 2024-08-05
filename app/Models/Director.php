<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class Director extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

    protected $table = 'director';
    protected $primaryKey = ['codigo_director', 'user_id'];
    public $incrementing = false;

    # ESTOS CAMPOS NO PUEDEN SER ASIGNADOS EN MASA
    protected $guarded = [];

    public function getKeyName()
    {
        return ['codigo_director', 'user_id'];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function estado_civil()
    {
        return $this->belongsTo(Estado::class, 'id_estado');
    }

    public function getFechaNacimientoAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getFechaIngresoAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
}
