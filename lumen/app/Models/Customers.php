<?php

namespace App\Models;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\Matcher\Contains;

class Customers extends Model
{
    protected $fillable = [
        "company_name",
        "trade_name",
        "person_type",
        "document",
        "state",
        "city",
        "email",
        "phone",
        "address",
        "status"
    ];

    protected $hidden = [
        "created_at",
        "updated_at"
    ];

    public $relateds = ["contacts", "customers_files"];

    const STATUSES = ["active" => "Ativo", "inactive" => "Inantivo", "prospect" => "Prospectivo"];

    public function contacts()
    {
        return $this->hasMany(Contacts::class, "customer_id");
    }

    public function customers_files()
    {
        return $this->hasMany(CustomersFiles::class, "customer_id")
            ->whereNull("customers_file_id")
            ->orderBy("id", "asc")
            ->with("relations");
    }

    public function startSave($data)
    {
        $this->fill($data[$this->getTable()]);
        $this->save();

        foreach ($this->relateds as $related) {
            if (isset($data[$related])) {
                $model = Helpers::getModelByTableName($related);
                foreach ($data[$related] as $dataNew) {
                    $new = new $model;
                    $new->fill($dataNew);
                    $new->setForeignKey($this->id);
                    $new->save();

                    if ($related === "customers_files") {
                        // if ($new->type === "file") {
                        //     $new->saveFile($dataNew['file']);
                        // }
                        foreach ($dataNew["relations"] ?? [] as $relation) {
                            $new->recursiveStore($this->id, $relation);
                        }
                    }
                }
            }
        }
    }

    public function startUpdate($data)
    {
        $this->fill($data[$this->getTable()]);
        $this->save();

        foreach ($this->relateds as $related) {
            $model = Helpers::getModelByTableName($related);
            $model->whereNotIn("id", $this->selectAllIds($this->filterWithId($data[$related])))->get();

            if ($related === "customers_files") {
                foreach ($data[$related] as $new) {

                    if (!isset($new["id"])) {
                        $related = new $model;
                        $related->fill($new);
                        $related->setForeignKey($this->id);
                        $related->save();
                    } else {
                        $related = CustomersFiles::find($new["id"]);
                    }

                    foreach ($new["relations"] ?? [] as $relation) {
                        $related->recursiveStore($this->id, $relation);
                    }
                }
            } else {
                foreach ($this->filterWithoutId($data[$related]) as $new) {
                    $newRelated = new $model;
                    $newRelated->fill($new);
                    $newRelated->setForeignKey($this->id);
                    $newRelated->save();
                }
            };
        }
    }

    private function filterWithoutId($arr)
    {
        return array_filter($arr, function ($filter) {
            return !isset($filter["id"]);
        });
    }

    private function filterWithId($arr)
    {
        return array_filter($arr, function ($filter) {
            return isset($filter["id"]);
        });
    }

    private function selectAllIds($arr)
    {
        return array_map(function ($map) {
            return $map["id"];
        }, $arr);
    }

    public function scopeTranslate($query)
    {
        return $query->select($this->getTable() . ".*", DB::raw("CASE status
        WHEN 'active' THEN 'Ativo'
        WHEN 'inactive' THEN 'Inativo'
        WHEN 'prospect' THEN 'Prospectivo'
        ELSE status END AS status"));
    }
}
