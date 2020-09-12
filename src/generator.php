<?php

namespace Gendiff\Generator;

use function Gendiff\Parser\parse;
use function Gendiff\DiffBuilder\buildDiff;
use function Gendiff\Formatters\BasicFormat\getOutputInBasicFormat;

function generateDiff($filePath1, $filePath2)
{
    $rawDataBefore = file_get_contents($filePath1);
    $rawDataAfter = file_get_contents($filePath2);
    $parsedDataBefore = parse($rawDataBefore, pathinfo($filePath1, PATHINFO_EXTENSION));
    $parsedDataAfter = parse($rawDataAfter, pathinfo($filePath2, PATHINFO_EXTENSION));
    $diff = buildDiff($parsedDataBefore, $parsedDataAfter);
    
    return getOutputInBasicFormat($diff);
}
