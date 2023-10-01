<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Storage;

class Files
{
    public static function save($string)
    {
        list($type) = explode(';', $string);
        list(, $extension) = explode('/', $type);
        $name = uniqid() . "." . $extension;

        Storage::disk(config("app.storage"))->put($name, file_get_contents($string));

        return $name;
    }

    public static function get($name)
    {
        return Storage::disk(config("app.storage"))->get($name);
    }

    public static function delete($name)
    {
        return Storage::disk(config("app.storage"))->delete($name);
    }
}
