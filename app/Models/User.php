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
            return $this->docente->primer_nombre . ' ' . $this->docente->apellido_paterno;
        } elseif ($this->estudiante) {
            return $this->estudiante->primer_nombre . ' ' . $this->estudiante->apellido_paterno;
        } elseif ($this->director) {
            return $this->director->primer_nombre . ' ' . $this->director->apellido_paterno;
        } elseif ($this->secretaria) {
            return $this->secretaria->primer_nombre . ' ' . $this->secretaria->apellido_paterno;
        } else {
            return 'Admin';
        }
    }

}
