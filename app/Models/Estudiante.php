<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    # ESTOS CAMPOS PUEDEN SER ASIGNADOS EN MASA
    protected $fillable = [ # $ : indica que fillable es una propiedad de la clase Estudiante
        'name',
        'dni',
        'email',
        'grade',
        'level',
        'section',
        'user_id',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($estudiante) {
            if ($estudiante->user) {
                $estudiante->user->delete();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
