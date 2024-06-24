<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    # ESTOS CAMPOS PUEDEN SER ASIGNADOS EN MASA
    protected $fillable = [ # $ : indica que fillable es una propiedad de la clase Estudiante
        'name',
        'dni',
        'email',
        'user_id',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($docente) {
            if ($docente->user) {
                $docente->user->delete();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
