<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CustomersFiles extends Model
{
    protected $fillable = [
        "type",
        "customers_id",
        "customers_file_id",
        "file"
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

            // if ($new->type === "file") {
            //     $new->saveFile($data["file"]);
            // }
        }

        foreach ($data["relations"] ?? [] as $relation) {
            $new->recursiveStore($foreign, $relation);
        }
    }

    // public function saveFile($base64)
    // {
    //     $extension = explode('/', explode(':', substr($base64, 0, strpos($base64, ';')))[1])[1];   // .jpg .png .pdf
    //     $replace = substr($base64, 0, strpos($base64, ',') + 1);
    //     $image = str_replace($replace, '', $base64);
    //     $image = str_replace(' ', '+', $image);
    //     $imageName = uniqid() . '.txt';
    //     Storage::disk('local')->put($imageName, base64_decode($image));
    //     $this->path = $imageName;
    //     $this->save();
    // }
}
