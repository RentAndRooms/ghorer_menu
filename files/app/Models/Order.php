<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'branch_id',
        'subtotal',
        'delivery_fee',
        'order_type',
        'tax',
        'total_amount',
        'delivery_address',
        'delivery_latitude',
        'delivery_longitude',
        'status',
        'payment_status',
        'payment_method',
        'paymentRef',
        'delivery_notes',
        'estimated_delivery_time'
    ];

    protected $casts = [
        'order_type' => 'string',
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
        'estimated_delivery_time' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public function scopeWithOrderDetails($query)
    {
        return $query->with(['items.food', 'items.extras', 'branch', 'user']); // Use 'items' instead of 'orderItems'
    }

    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity'); // Use items instead of orderItems
    }


        public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function getStatuses()
    {
        return [
            'pending',
            'confirmed',
            'delivered',
            'cancelled',
        ];
    }

    public static function getPaymentStatuses()
    {
        return [
            'pending',
            'paid',
            'failed',
            'refunded',
        ];
    }
    // In Order.php
    public function getStatusTimeline()
    {
        $allStatuses = [
            'pending' => [
                'label' => 'Order Placed',
                'icon' => 'fa-shopping-cart'
            ],
            'confirmed' => [
                'label' => 'Order Confirmed',
                'icon' => 'fa-check-circle'
            ],
            // 'preparing' => [
            //     'label' => 'Preparing',
            //     'icon' => 'fa-utensils'
            // ],
            // 'ready_for_delivery' => [
            //     'label' => 'Ready for Delivery',
            //     'icon' => 'fa-box'
            // ],
            // 'out_for_delivery' => [
            //     'label' => 'Out for Delivery',
            //     'icon' => 'fa-truck'
            // ],
            'delivered' => [
                'label' => 'Delivered',
                'icon' => 'fa-check-double'
            ]
        ];

        $currentStatusFound = false;
        $timeline = [];

        foreach ($allStatuses as $statusKey => $statusData) {
            // Skip if order is cancelled and we haven't reached the status when it was cancelled
            if ($this->status === 'cancelled' && !$currentStatusFound) {
                continue;
            }

            $isCompleted = false;

            // If this status is the current one or has been passed
            if ($this->status === $statusKey) {
                $currentStatusFound = true;
                $isCompleted = true;
            } elseif (!$currentStatusFound) {
                $isCompleted = true;
            }

            $timeline[] = [
                'status' => $statusKey,
                'label' => $statusData['label'],
                'icon' => $statusData['icon'],
                'timestamp' => $this->created_at, // Since you don't have individual timestamps
                'completed' => $isCompleted
            ];
        }

        // Add cancelled status if order is cancelled
        if ($this->status === 'cancelled') {
            $timeline = [[
                'status' => 'cancelled',
                'label' => 'Order Cancelled',
                'icon' => 'fa-times-circle',
                'timestamp' => $this->updated_at,
                'completed' => true
            ]];
        }
        return $timeline;
    }

}
