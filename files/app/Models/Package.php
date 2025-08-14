<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $guarded = ['id'];
    public function foods(){
        return $this->belongsToMany(Food::class, 'package_food');
    }
}
