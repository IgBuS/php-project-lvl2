<?php

namespace Biserg\Gendiff\Generator;

use function Biserg\Gendiff\Parser\parse;
use function Biserg\Gendiff\DiffBuilder\buildDiff;
use function Biserg\Gendiff\Formater\format;

function generateDiff($filePath1, $filePath2, $formatName = 'basic')
{
    [ 'extention' => $dataType1, 'content' => $rawData1 ] = getFileData($filePath1);
    $data1 = parse($rawData1, $dataType1);

    [ 'extention' => $dataType2, 'content' => $rawData2 ] = getFileData($filePath2);
    $data2 = parse($rawData2, $dataType2);

    $diff = buildDiff($data1, $data2);
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
