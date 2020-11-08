<?php

namespace Biserg\Gendiff\Formater;

use Biserg\Gendiff\Formatters\Basic;
use Biserg\Gendiff\Formatters\Plain;
use Biserg\Gendiff\Formatters\Json;

function format($diff, $format)
{
    $formatters = [
        'basic' => fn($diff) => Basic\render($diff),
        'plain' => fn($diff) => Plain\render($diff),
        'json' => fn($diff) => Json\render($diff)
    ];

    if (!array_key_exists($format, $formatters)) {
        throw new \Exception("Format '$format' is not supported");
    }
    return $formatters[$format]($diff);
}
