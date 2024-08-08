<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'esActivo',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //Relación uno a uno com docente
    public function docente()
    {
        return $this->hasOne(Docente::class);
    }

    //Relación uno a uno con estudiante
    public function estudiante()
    {
        return $this->hasOne(Estudiante::class);
    }

    //Relación uno a uno con estudiante
    public function secretaria()
    {
        return $this->hasOne(Secretaria::class);
    }

    //Relación uno a uno con estudiante
    public function director()
    {
        return $this->hasOne(Director::class);
    }

    //Relación uno a uno con domicilio
    public function domicilio()
    {
        return $this->hasOne(Domicilio::class);
    }

    public function getFullNameAttribute()
    {
        if ($this->docente) {
            return trim($this->docente->primer_nombre . ' ' . $this->docente->otros_nombres . ' ' . $this->docente->apellido_paterno . ' ' . $this->docente->apellido_materno);
        } elseif ($this->estudiante) {
            return trim($this->estudiante->primer_nombre . ' ' . $this->estudiante->otros_nombres . ' ' . $this->estudiante->apellido_paterno . ' ' . $this->estudiante->apellido_materno);
        } elseif ($this->director) {
            return trim($this->director->primer_nombre . ' ' . $this->director->otros_nombres . ' ' . $this->director->apellido_paterno . ' ' . $this->director->apellido_materno);
        } elseif ($this->secretaria) {
            return trim($this->secretaria->primer_nombre . ' ' . $this->secretaria->otros_nombres . ' ' . $this->secretaria->apellido_paterno . ' ' . $this->secretaria->apellido_materno);
        } else {
            return 'Admin';
        }
    }


}
