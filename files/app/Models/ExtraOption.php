<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtraOption extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'food_id',
        'name',
        'price',
        'is_available',
        'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function orderItemExtras()
    {
        return $this->hasMany(OrderItemExtra::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
}
