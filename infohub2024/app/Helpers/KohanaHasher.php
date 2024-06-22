<?php

namespace App\Helpers;

class KohanaHasher
{
    public static function hash($password, $createTime)
    {
        $salt = substr(md5($createTime), 0, 6);
        return hash('sha256', $password . $salt);
    }

    public static function check($value, $hashedValue, $createTime)
    {
        return hash_equals(self::hash($value, $createTime), $hashedValue);
    }
}
