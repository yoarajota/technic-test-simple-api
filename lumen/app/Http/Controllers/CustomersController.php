<?php

namespace App\Http\Controllers;

use App\Helpers\Files;
use App\Jobs\ClearTmp;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function downloadFile(Request $request)
    {
        $path = $request->path;
        file_put_contents("tmp/" . $path, Files::get($request->path));
        $response = response()->download("tmp/" . $path);
        dispatch((new ClearTmp(base_path("public/tmp/" . $path))));
        return $response;
    }
}
