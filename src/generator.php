<?php

namespace Gendiff\Generator;

use function Gendiff\Parser\parse;
use function Gendiff\DiffBuilder\buildDiff;
use function Gendiff\Formatters\BasicFormat\getOutputInBasicFormat;
use function Gendiff\Formatters\PlainFormat\getOutputInPlainFormat;

function generateDiff($filePath1, $filePath2, $format = 'basic')
{
    $rawDataBefore = file_get_contents($filePath1);
    $rawDataAfter = file_get_contents($filePath2);
    $parsedDataBefore = parse($rawDataBefore, pathinfo($filePath1, PATHINFO_EXTENSION));
    $parsedDataAfter = parse($rawDataAfter, pathinfo($filePath2, PATHINFO_EXTENSION));
    $diff = buildDiff($parsedDataBefore, $parsedDataAfter);
    
    return format($diff, $format);
}

function format($diff, $format)
{
    $ways = [
        'basic' => function ($diff) {
            return getOutputInBasicFormat($diff);
        },
        'plain' => function ($diff) {
            return getOutputInPlainFormat($diff);
        }
    ];
    return $ways[$format]($diff);
}
