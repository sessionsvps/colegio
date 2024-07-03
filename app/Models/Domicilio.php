<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domicilio extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $guarded = []; // guarded indica que campos no pueden asignarse masivamente


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
