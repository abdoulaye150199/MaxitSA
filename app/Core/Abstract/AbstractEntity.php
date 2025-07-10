<?php 

namespace App\core\abstract;

abstract class AbstractEntity
{
    abstract  static function toObject(array $data);
    public static function toJson(array $data): string
    {
        return json_encode(static::toArray($data));
    }

    abstract static function toArray():array;
}