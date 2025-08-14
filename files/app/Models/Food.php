<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Food extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_id',
        'category_id',
        'name',
        'description',
        'base_price',
        'preparation_time',
        'image_path',
        'is_vegetarian',
        'is_spicy',
        'half_price',
        'allergens',
        'is_available',
        'sort_order'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_vegetarian' => 'boolean',
        'is_spicy' => 'boolean',
        'allergens' => 'array',
        'is_available' => 'boolean',
        'preparation_time' => 'integer',
        'sort_order' => 'integer'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function packages(){
        return $this->belongsToMany(Package::class, 'package_food');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function extraOptions()
    {
        return $this->hasMany(ExtraOption::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
}
