<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UrlGroup extends Model
{
    
    protected $fillable = [
        "name",
        "status",
    ];
    use SoftDeletes;
    public function urls(){
        return $this->belongsToMany(Subscribe::class, 'url_url_group', 'url_group_id', 'url_id');
    }
}
