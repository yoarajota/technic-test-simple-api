<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registers extends Model
{
    protected $fillable = [
        "name",
        "icon",
    ];

    protected $hidden = [
        "created_at",
        "updated_at"
    ];
}
