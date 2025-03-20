<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    protected $fillable = [
        "name",
        "is_free",
        "status",
        "content"
    ];
}
