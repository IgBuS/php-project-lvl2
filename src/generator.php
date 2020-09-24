<?php

namespace Biserg\Gendiff\Generator;

use function Biserg\Gendiff\Parser\parse;
use function Biserg\Gendiff\DiffBuilder\buildDiff;
use function Biserg\Gendiff\Formatters\Formater\format;

function generateDiff($filePath1, $filePath2, $format = 'basic')
{
    $rawDataBefore = file_get_contents($filePath1);
    $rawDataAfter = file_get_contents($filePath2);
    $parsedDataBefore = parse($rawDataBefore, pathinfo($filePath1, PATHINFO_EXTENSION));
    $parsedDataAfter = parse($rawDataAfter, pathinfo($filePath2, PATHINFO_EXTENSION));
    $diff = buildDiff($parsedDataBefore, $parsedDataAfter);
    
    return format($diff, $format);
}

