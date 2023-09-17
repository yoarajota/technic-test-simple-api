<?php

namespace App\Http\Middleware;

use App\Models\Forms;
use Closure;
use Exception;
use Illuminate\Support\Facades\DB;

class Validators
{
    public function handle($request, Closure $next)
    {
        try {

            $forms = Forms::getAllForms($request->register);

            foreach ($forms as $form) {
                foreach ($form->fields as $field) {
                    foreach ($field->validations as $validation) {
                        switch ($validation->type) {
                            case "required":
                                if ($form->type === "list") {
                                    foreach ($request[$form->name] as $register) {
                                        $this->required($field, $register);
                                    }
                                } else {
                                    $this->required($field, $request[$form->name]);
                                }
                                break;
                            case "unique":
                                if ($form->type === "list") {
                                    foreach ($request[$form->name] as $register) {
                                        $this->unique($form->name, $field, $register);
                                    }
                                } else {
                                    $this->unique($form->name, $field, $request[$form->name]);
                                }
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        } catch (Exception $exception) {
            return response(["status" => "error", "message" => $exception->getMessage()], 400);
        }

        return $next($request);
    }

    private function unique($register, $field, $data)
    {
        throw_if(DB::table($register)
            ->when(isset($data["id"]), function ($when) use ($data) {
                $when->where("id", "!=", $data["id"]);
            })
            ->where($field->name, $data[$field->name])
            ->exists(), new Exception("Já existe um cadastro com o valor do campo $field->label do formulário."));
    }

    private function required($field, $data)
    {
        throw_if(!isset($data[$field->name]), new Exception("Campo $field->label é obrigatório!"));
    }
}
