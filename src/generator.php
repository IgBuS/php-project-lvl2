<?php

namespace Biserg\Gendiff\Generator;

use function Biserg\Gendiff\Parser\parse;
use function Biserg\Gendiff\DiffBuilder\buildDiff;
use function Biserg\Gendiff\Formatters\Formater\format;

function generateDiff($filePath1, $filePath2, $formatName = 'basic')
{

    $rawDataBefore = getFileData($filePath1);
    $rawDataAfter = getFileData($filePath2);

    $parsedDataBefore = parse($filePath1, $rawDataBefore);
    $parsedDataAfter = parse($filePath2, $rawDataAfter);

    $diff = buildDiff($parsedDataBefore, $parsedDataAfter);
    
    return format($diff, $formatName);
}

function getFileData($filePath)
{
    if (!file_exists($filePath)) {
        throw new \Exception("File '$filepath' does not exist");
    } else {
        return file_get_contents($filePath);
    }
}
