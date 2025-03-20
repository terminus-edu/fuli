<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Url extends Model
{
    protected $fillable = [
        "title",
        "url",
        "cover",
        "status"
    ];

    public function url_groups(){
        return $this->belongsToMany(UrlGroup::class, 'url_url_group', 'url_id', 'url_group_id');
    }
}
