<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validations extends Model
{
    protected $fillable = [
        "type",
        "field_id",
    ];
    protected $hidden = [
        "created_at",
        "updated_at"
    ];
}
