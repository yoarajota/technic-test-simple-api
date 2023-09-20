<?php

namespace App\Helpers;

class Helpers
{
    public static function getModelByTableName($string)
    {
        $className = 'App\\Models\\' . preg_replace_callback('/_(.)/', function ($matches) {
            return strtoupper($matches[1]);
        }, ucfirst($string));
        $model = null;
        if (class_exists($className)) {
            return new $className;
        }

        return $model;
    }
}
