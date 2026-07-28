<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_BLOCKED = 'blocked';

    public const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_BLOCKED,
    ];

    public const STATUS_LABELS = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_BLOCKED => 'Blocked',
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'phone',
        'password',
        'status',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
