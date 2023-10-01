<?php

namespace App\Models;

use App\Helpers\Files;
use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
                        if ($new->isFile()) {
                            $new->path = Files::save($dataNew["file"]);
                            $new->save();
                        }
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


            if ($related === "customers_files") {
                $model->whereNotIn("id", $this->selectAllIds($this->filterWithId($data[$related])))->each(function ($each) {
                    if ($each->isFile()) {
                        Files::delete($each->path);
                    }
                    $each->delete();
                });

                foreach ($data[$related] as $new) {
                    if (!isset($new["id"])) {
                        $related = new $model;
                        $related->fill($new);
                        $related->setForeignKey($this->id);
                        $related->save();
                        if ($related->isFile()) {
                            $related->path = Files::save($new["file"]);
                            $related->save();
                        }
                    } else {
                        $related = CustomersFiles::find($new["id"]);
                    }

                    foreach ($new["relations"] ?? [] as $relation) {
                        $related->recursiveStore($this->id, $relation);
                    }
                }
            } else {
                $model->whereNotIn("id", $this->selectAllIds($this->filterWithId($data[$related])))->delete();
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
        return $query->select($this->getTable() . ".*", DB::raw(
            "CASE status
        WHEN 'active' THEN 'Ativo'
        WHEN 'inactive' THEN 'Inativo'
        WHEN 'prospect' THEN 'Prospectivo'
        ELSE status END AS status"
        ), "status as real_status", DB::raw(
            "CASE person_type
        WHEN 'natural' THEN 'Física'
        WHEN 'legal' THEN 'Jurídica'
        ELSE person_type END AS person_type"
        ), "person_type as real_person_type");
    }
}
