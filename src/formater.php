<?php

namespace Biserg\Gendiff\Formater;

use function Biserg\Gendiff\Formatters\BasicFormat\getOutputInBasicFormat;
use function Biserg\Gendiff\Formatters\PlainFormat\getOutputInPlainFormat;
use function Biserg\Gendiff\Formatters\JsonFormat\getOutputInJsonFormat;

function format($diff, $format)
{
    $ways = [
        'basic' => function ($diff) {
            return getOutputInBasicFormat($diff);
        },
        'plain' => function ($diff) {
            return getOutputInPlainFormat($diff);
        },
        'json' => function ($diff) {
            return getOutputInJsonFormat($diff);
        }
    ];

    if (!array_key_exists($format, $ways)) {
        throw new \Exception("Format '$format' is not supported");
    }
    return $ways[$format]($diff);
}
