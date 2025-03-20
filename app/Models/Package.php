<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    protected $fillable = [
        'name',
        'price',
        "duration",
        "status"
    ];
    use SoftDeletes;
    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function subscribes(){
        return $this->belongsToMany(Subscribe::class, 'package_subscribe', 'package_id', 'subscribe_id');
    }
}
