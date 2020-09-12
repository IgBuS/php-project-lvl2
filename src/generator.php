<?php

namespace Gendiff\generator;

use function Gendiff\parser\parse;
use function Gendiff\diffBuilder\buildDiff;
use function Gendiff\shapers\basicFormat\getOutput;

function generateDiff($filePath1, $filePath2)
{
    $rawDataBefore = file_get_contents($filePath1);
    $rawDataAfter = file_get_contents($filePath2);
    $parsedDataBefore = parse($rawDataBefore, pathinfo($filePath1, PATHINFO_EXTENSION));
    $parsedDataAfter = parse($rawDataAfter, pathinfo($filePath2, PATHINFO_EXTENSION));
    $diff = buildDiff($parsedDataBefore, $parsedDataAfter);
    $result = getOutput($diff);
    return "{\n{$result}\n}";
}
