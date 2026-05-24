<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PREPARING = 'preparing';
    public const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_PICKED_UP = 'picked_up';
    public const PAYMENT_METHOD_COD = 'cash_on_delivery';
    public const PAYMENT_STATUS_PENDING = 'pending';

    public static function allStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_PREPARING,
            self::STATUS_PICKED_UP,
            self::STATUS_OUT_FOR_DELIVERY,
            self::STATUS_DELIVERED,
            self::STATUS_CANCELLED,
        ];
    }

    public static function adminManageableStatuses(): array
    {
        return self::allStatuses();
    }

    public static function riderUpdatableStatuses(): array
    {
        return [
            self::STATUS_PICKED_UP,
            self::STATUS_OUT_FOR_DELIVERY,
            self::STATUS_DELIVERED,
        ];
    }

    public static function historyStatuses(): array
    {
        return [
            self::STATUS_DELIVERED,
            self::STATUS_CANCELLED,
        ];
    }

    public static function currentStatuses(): array
    {
        return array_values(array_diff(self::allStatuses(), self::historyStatuses()));
    }

    public function isTerminal(): bool
    {
        return in_array($this->status, self::historyStatuses(), true);
    }

    public static function paymentMethods(): array
    {
        return [
            self::PAYMENT_METHOD_COD,
        ];
    }

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'rider_id',
        'status',
        'total_price',
        'delivery_address',
        'payment_method',
        'payment_status',
        'payment_transaction_ref',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }
}
