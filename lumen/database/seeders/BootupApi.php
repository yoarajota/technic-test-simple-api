<?php

namespace Database\Seeders;

use App\Models\Fields;
use App\Models\Forms;
use App\Models\Options;
use App\Models\Registers;
use App\Models\Validations;
use Illuminate\Database\Seeder;

class BootupApi extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $register = Registers::firstOrCreate([
            "name" => "customers",
            "icon" => "person"
        ]);

        $firstForm = Forms::firstOrCreate([
            "name" => "customers",
            "register_id" => $register->id,
            "type" => "form"
        ]);

        $arr = [
            [
                "name" => "company_name",
                "label" => "Razão Social",
                "type" => "text"
            ],
            [
                "name" => "trade_name",
                "label" => "Nome Fantasia",
                "type" => "text"
            ],
            [
                "name" => "person_type",
                "label" => "Tipo de Pessoa",
                "type" => "select",
                "options" => [
                    ["value" => "natural", "text" => "Física"],
                    ["value" => "legal", "text" => "Jurídica"],
                ]
            ],
            [
                "name" => "document",
                "label" => "Documento",
                "type" => "text",
                "validations" => [
                    ['type' => "unique"]
                ]
            ],
            [
                "name" => "state",
                "label" => "Estado",
                "type" => "text"
            ],
            [
                "name" => "city",
                "label" => "Cidade",
                "type" => "text"
            ],
            [
                "name" => "email",
                "label" => "Email",
                "type" => "email"
            ],
            [
                "name" => "phone",
                "label" => "Telefone",
                "type" => "number"
            ],
            [
                "name" => "address",
                "label" => "Endereço",
                "type" => "text"
            ],
            [
                "name" => "status",
                "label" => "Status",
                "type" => "select",
                "options" => [
                    ["value" => "active", "text" => "Ativo", "class" => "bg-green"],
                    ["value" => "inactive", "text" => "Inativo", "class" => "bg-gray"],
                    ["value" => "prospect", "text" => "Prospecto", "class" => "bg-purple"],
                ]
            ]
        ];

        foreach ($arr as $a) {
            $options = false;
            if (isset($a["options"])) {
                $options = $a["options"];
                unset($a["options"]);
            }
            $validations = false;
            if (isset($a["validations"])) {
                $validations = $a["validations"];
                unset($a["validations"]);
            }

            $field = Fields::firstOrCreate([
                ...$a,
                "form_id" => $firstForm->id,
            ]);

            if ($options) {
                foreach ($options as $option) {
                    Options::firstOrCreate([...$option, "field_id" => $field->id]);
                }
            }

            if ($validations) {
                foreach ($validations as $validation) {
                    $validation["field_id"] = $field->id;
                    Validations::firstOrCreate($validation);
                }
            }

            Validations::firstOrCreate(['type' => "required", "field_id" => $field->id]);
        }

        $firstForm = Forms::firstOrCreate([
            "name" =>  "contacts",
            "register_id" =>  $register->id,
            "type" => "list"
        ]);

        $arr = [
            [
                "name" => "name",
                "label" => "Nome",
                "type" => "text"
            ],
            [
                "name" => "email",
                "label" => "Email",
                "type" => "email"
            ],
            [
                "name" => "phone",
                "label" => "Telefone",
                "type" => "number"
            ],
            [
                "name" => "function",
                "label" => "Função",
                "type" => "text"
            ]
        ];

        foreach ($arr as $a) {
            $options = false;
            if (isset($a["options"])) {
                $options = $a["options"];
                unset($a["options"]);
            }

            $field = Fields::firstOrCreate([
                ...$a,
                "form_id" => $firstForm->id,
            ]);

            Validations::firstOrCreate(['type' => "required", "field_id" => $field->id]);
        }
        
        $firstForm = Forms::firstOrCreate([
            "name" =>  "customers_files",
            "register_id" =>  $register->id,
            "type" => "files"
        ]);
    }
}
