<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fields extends Model
{
    protected $fillable = [
        "name",
        "label",
        "type",
        "form_id",
    ];
    protected $hidden = [
        "created_at",
        "updated_at"
    ];

    public function options()
    {
        return $this->hasMany(Options::class, "field_id");
    }

    public function validations()
    {
        return $this->hasMany(Validations::class, "field_id");
    }
}
