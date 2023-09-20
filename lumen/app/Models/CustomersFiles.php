<?php

namespace App\Models;

use App\Helpers\Files;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CustomersFiles extends Model
{
    protected $fillable = [
        "type",
        "customers_id",
        "customers_file_id",
        "path"
    ];

    protected $hidden = [
        "created_at",
        "updated_at"
    ];

    public function relations()
    {
        return $this->hasMany(CustomersFiles::class, "customers_file_id");
    }

    public function setForeignKey(int $foreign)
    {
        $this->customer_id = $foreign;
    }

    public function setForeignKeyRelation(int $foreign)
    {
        $this->customers_file_id = $foreign;
    }

    public function recursiveStore($foreign, $data)
    {
        if (!isset($data["id"])) {
            $new = new $this;
            $new->fill($data);
            $new->setForeignKey($foreign);
            $new->setForeignKeyRelation($this->id);
            $new->save();

            if ($new->type === "file") {
                $new->path = Files::save($data["file"]);
                $new->save();
            }
        }

        foreach ($data["relations"] ?? [] as $relation) {
            $new->recursiveStore($foreign, $relation);
        }
    }
}
