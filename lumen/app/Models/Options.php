<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Options extends Model
{
    protected $fillable = [
        "value",
        "text",
        "field_id",
    ];
    protected $hidden = [
        "created_at",
        "updated_at"
    ];
}
