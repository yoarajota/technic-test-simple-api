<?php

namespace App\Http\Controllers;

use App\Models\Registers;
use Exception;
use Illuminate\Http\Request;

class RegistersController extends Controller
{
    public function index(Request $request)
    {
        try {
            return $this->success(["menu" => Registers::all()->toArray()]);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }
}
