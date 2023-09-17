<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forms extends Model
{
    protected $fillable = [
        "names",
        "register_id",
        "type",
    ];

    protected $hidden = [
        "created_at",
        "updated_at"
    ];

    public function fields()
    {
        return $this->hasMany(Fields::class, "form_id");
    }

    public static function getAllForms($register)
    {
        return self::with("fields", "fields.options", "fields.validations")
            ->select("forms.*")
            ->join("registers", function ($join) use ($register) {
                return $join->on("register_id", "registers.id")->where("registers.name", $register);
            })
            ->get();
    }
}
