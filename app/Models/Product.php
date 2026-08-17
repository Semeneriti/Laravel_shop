<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
    ];

    public const STATUS_LABELS = [
        self::STATUS_ACTIVE => 'Активен',
        self::STATUS_INACTIVE => 'Неактивен',
    ];

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'stock',
        'sku',
        'category_id',
        'status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }
}
