<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    public function member(){
        return $this->belongsTo(Member::class);
    }
    public function agent(){
        return $this->belongsTo(User::class,"agent_id");
    }
    public function package(){
        return $this->belongsTo(Package::class);
    }
}
