<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_id',
        'branch_id',
        'food_rating',
        'delivery_rating',
        'comment',
        'is_published'
    ];

    protected $casts = [
        'food_rating' => 'integer',
        'delivery_rating' => 'integer',
        'is_published' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
