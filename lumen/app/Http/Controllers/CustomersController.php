<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function downloadFile(Request $request)
    {
        return response()->download('files/' . $request->path);
    }
}
