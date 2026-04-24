<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'full_name',
        'phone',
        'position',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
     public function doctorShifts()
    {
        return $this->hasMany(Shift::class, 'doctor_user_id');
    }

    public function assistantShifts()
    {
        return $this->hasMany(Shift::class, 'assistant_user_id');
    }

    public function doctorSceneCases()
    {
        return $this->hasMany(SceneCase::class, 'doctor_user_id');
    }

    public function assistantSceneCases()
    {
        return $this->hasMany(SceneCase::class, 'assistant_user_id');
    }

    public function createdSceneCases()
    {
        return $this->hasMany(SceneCase::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isDoctor(): bool
    {
        return $this->hasRole('doctor');
    }

    public function isAssistant(): bool
    {
        return $this->hasRole('assistant');
    }

    public function displayName(): string
    {
        return $this->full_name ?: $this->name;
    }
}
