<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;
    public function subscribes(){
        return $this->belongsToMany(Subscribe::class, 'member_subscribe', 'member_id', 'subscribe_id');
    }
    public function agent(){
        return $this->belongsTo(related: User::class, foreignKey: 'agent_id');
    }
    public function orders(){
        return $this->hasMany(related: Order::class);
    }
}
