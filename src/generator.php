<?php

namespace Biserg\Gendiff\Generator;

use function Biserg\Gendiff\Parser\parse;
use function Biserg\Gendiff\DiffBuilder\buildDiff;
use function Biserg\Gendiff\Formater\format;

function generateDiff($filePath1, $filePath2, $formatName = 'basic')
{

    $rawDataBefore = getFileData($filePath1);
    $rawDataAfter = getFileData($filePath2);

    $parsedDataBefore = parse($rawDataBefore);
    $parsedDataAfter = parse($rawDataAfter);

    $diff = buildDiff($parsedDataBefore, $parsedDataAfter);
    
    return format($diff, $formatName);
}

function getFileData($filePath)
{
    if (!file_exists($filePath)) {
        throw new \Exception("File '$filepath' does not exist");
    }
    $result = [
        'content' => file_get_contents($filePath),
        'extention' => pathinfo($filePath, PATHINFO_EXTENSION)
    ];
    return $result;
}
