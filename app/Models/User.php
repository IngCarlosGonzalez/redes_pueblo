<?php

namespace App\Models;

use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmail;
use Laravel\Jetstream\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
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
        'name',
        'email',
        'password',
        'is_active',
        'is_admin',
        'level_id',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casteos = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'bandera',
        'palabra',
    ];

    public function getBanderaAttribute()
    {
        return false;
    }

    public function getPalabraAttribute()
    {
        return '';
    }

    public function contactos(): HasMany
    {
        return $this->hasMany(Contacto::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole([
            'ADMINISTRADOR', 
            'COORDINADOR', 
            'OPERADOR',
            'PROMOTOR'
        ]);
    }

    public function activate(): void
    {
        $this->is_active = true;
        $this->save();
    }

    public function deactivate(): void
    {
        $this->is_active = false;
        $this->save();
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmail);
    }

}
