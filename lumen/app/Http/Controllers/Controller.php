<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Fields;
use App\Models\Forms;
use Exception;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use stdClass;

class Controller extends BaseController
{

    public function index(Request $request)
    {
        try {
            $fields = Fields::with("options")
                ->join("forms", "forms.id", "form_id")
                ->select("fields.*")
                ->where("forms.name", $request->register)
                ->get();

            return $this->success(["data" => $request->model->orderBy("id", "desc")->translate()->get(), "fields" => $fields]);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    public function create(Request $request)
    {
        try {
            $forms = Forms::getAllForms($request->register);
            return $this->success(["forms" => $forms, "data" => $this->mountEmptyData($forms)]);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->model->startSave($request->all());
            return $this->success(["message" => "Salvo com sucesso!", "status" => 200]);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $forms = Forms::getAllForms($request->register);
            $model = Helpers::getModelByTableName($request->register);

            $data = [];
            $table = $model->getTable();
            foreach ($model::with($model->relateds)->where("id", $id)->first()?->toArray() ?? [] as $key => $value) {
                if (in_array($key, $model->relateds)) {
                    $data[$key] = $value;
                } else {
                    $data[$table][$key] = $value;
                }
            }

            return $this->success(["forms" => $forms, "data" => $this->checkIfIsEmpty($data, $forms)]);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->model->find($id)->startUpdate($request->all());
            return $this->success(["message" => "Atualizado com sucesso!", "status" => "success"]);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $request->model->where("id", $id)->delete($id);
            return $this->success(["message" => "Excluído com sucesso!", "status" => "success"]);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    protected function success($data, $code = 200)
    {
        return response(array_merge($data, ["status" => "success"]), $code);
    }

    protected function error($exception, $code = 400)
    {
        return response(["status" => "error", "message" => "Falha ao realizar ação."], $code);
    }

    private function mountEmptyData($forms)
    {
        $empty = [];

        foreach ($forms as $form) {
            $obj = [];
            foreach ($form->fields as $field) {
                $obj[$field->name] = null;
            }

            if ($form->type === "form") {
                $empty[$form->name] = $obj;
            } elseif ($form->type === "list") {
                $empty[$form->name] = [$obj];
            } else {
                $empty[$form->name] = [["type" => "folder"]];
            }
        }
        return $empty;
    }

    private function checkIfIsEmpty($data, $forms)
    {
        foreach ($forms as $form) {
            if (isset($data[$form->name]) && $form->type === "form") {
                continue;
            }

            $obj = [];
            foreach ($form->fields as $field) {
                $obj[$field->name] = null;
            }

            if ($form->type === "form") {
                $data[$form->name] = $obj;
            } elseif ($form->type === "list") {
                if ($data[$form->name]) {
                    array_unshift($data[$form->name], $obj);
                } else {
                    $data[$form->name] = [$obj];
                }
            }
        }
        return $data;
    }
}
