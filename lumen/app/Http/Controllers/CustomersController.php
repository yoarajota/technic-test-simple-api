<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CustomersController extends Controller
{
    // public function downloadFile(Request $request)
    // {
    //     return new BinaryFileResponse(Storage::disk("local")->get($request->path), 200);
    // }
}
