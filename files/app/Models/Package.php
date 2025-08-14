<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $guarded = ['id'];
    public function foods(){
        return $this->belongsToMany(Food::class, 'package_food');
    }

    public function branch(){
        return $this->belongsTo(Branch::class, 'branche_id');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
