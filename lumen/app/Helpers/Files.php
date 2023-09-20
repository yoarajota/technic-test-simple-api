<?php

namespace App\Helpers;

class Files
{
    public static function save($string)
    {
        list($type) = explode(';', $string);
        list(, $extension) = explode('/', $type);
        $path = uniqid() . "." . $extension;
        file_put_contents('files/' . $path, file_get_contents($string));
        return $path;
    }
}
