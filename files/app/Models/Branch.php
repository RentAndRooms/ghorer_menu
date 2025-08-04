<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'opening_hours',
        'contact_number',
        'delivery_radius',
        'is_active',
        'image',
        'district_id',
        'thana_id',
        'local_id'
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'delivery_radius' => 'decimal:2',
        'is_active' => 'boolean'
    ];


    /**
     * Scope a query to add distance calculation from a point
     */
    public function scopeWithDistance(Builder $query, $latitude, $longitude)
    {
        return $query->select([
            'branches.*',
            DB::raw("(
                6371 * acos(
                    cos(radians($latitude)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians($longitude)) +
                    sin(radians($latitude)) *
                    sin(radians(latitude))
                )
            ) AS distance")
        ]);
    }

    /**
     * Scope a query to only include branches within a certain distance
     */
    public function scopeWithinDistance(Builder $query, $latitude, $longitude, $distance)
    {
        return $query->withDistance($latitude, $longitude)
            ->having('distance', '<=', $distance);
    }

    /**
     * Scope a query to only include active branches
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if branch is currently open
     */
    public function isOpen(): bool
    {
        if (empty($this->opening_hours)) {
            return false;
        }

        $now = now();
        $currentDay = strtolower($now->format('l')); // get current day name
        $currentTime = $now->format('H:i');

        foreach ($this->opening_hours as $hours) {
            if (strtolower($hours['day']) === $currentDay) {
                return $currentTime >= $hours['open'] && $currentTime <= $hours['close'];
            }
        }

        return false;
    }

    /**
     * Check if delivery is available to a location
     */
    public function isDeliveryAvailable($latitude, $longitude): bool
    {
        if (!$this->is_active || !$this->delivery_radius) {
            return false;
        }

        $distance = $this->getDistanceTo($latitude, $longitude);
        return $distance <= $this->delivery_radius;
    }

    /**
     * Calculate distance to a point
     */
    public function getDistanceTo($latitude, $longitude): float
    {
        $earthRadius = 6371; // kilometers

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
