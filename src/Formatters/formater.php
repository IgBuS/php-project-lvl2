<?php

namespace Biserg\Gendiff\Formatters\Formater;

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
    return $ways[$format]($diff);
}
