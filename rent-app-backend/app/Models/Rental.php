<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rental extends Model
{
     use HasFactory;
     protected $fillable = [
        'user_id',
        'house_id',
        'start_date',
        'end_date',
        'total_price',
        'number_of_guests',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_price' => 'decimal:2',
        'status' => 'string',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';

    public static function validStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_REJECTED,
            self::STATUS_CANCELLED,
        ];
    }

    protected static function booted()
    {
        static::saving(function ($rental) {
            if (!in_array($rental->status, self::validStatuses())) {
                throw new \InvalidArgumentException("Invalid status: {$rental->status}");
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }
}
