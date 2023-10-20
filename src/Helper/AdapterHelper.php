<?php

namespace App\Helper;

class AdapterHelper
{
    const ROOT = '\\App\\Integration\\';

    const Mickey = [
        'name' => 'MICKEY',
        'class_name' => 'Mickey',
        'path' => 'Adapters\\Mickey',
        "service_url" => 'http://www.mocky.io/v2/5d47f24c330000623fa3ebfa'
    ];

    const Gordy = [
        'name' => 'GORDY',
        'class_name' => 'Gordy',
        'path' => 'Adapters\\Gordy',
        'service_url' => 'http://www.mocky.io/v2/5d47f235330000623fa3ebf7'
    ];

    public static array $adapterMap = [
        'gordy' => self::Gordy,
        'mickey' => self::Mickey
    ];

    public static function get($name)
    {
        $name = strtolower($name);
        return self::$adapterMap[$name] ?? [];
    }
}