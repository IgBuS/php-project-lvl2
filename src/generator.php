<?php

namespace Biserg\Gendiff\Generator;

use function Biserg\Gendiff\Parser\parse;
use function Biserg\Gendiff\DiffBuilder\buildDiff;
use function Biserg\Gendiff\Formatters\Formater\format;

const AVALIABLE_FORMATS = ['json', 'yaml', 'yml', ];

function generateDiff($filePath1, $filePath2, $format = 'basic')
{

    $rawDataBefore = getFileData($filePath1);
    $rawDataAfter = getFileData($filePath2);


    $firstFileExt = pathinfo($filePath1, PATHINFO_EXTENSION);
    $secondFileExt = pathinfo($filePath2, PATHINFO_EXTENSION);
    if (in_array($firstFileExt, AVALIABLE_FORMATS) && in_array($secondFileExt, AVALIABLE_FORMATS)) {
        $parsedDataBefore = parse($rawDataBefore, $firstFileExt);
        $parsedDataAfter = parse($rawDataAfter, $secondFileExt);
    } else {
        throw new Exception("Extention of one or both files is not suported");
    }

    $diff = buildDiff($parsedDataBefore, $parsedDataAfter);
    
    return format($diff, $format);
}

function getFileData($filePath)
{
    if (!file_exists($filePath)) {
        throw new \Exception("File '$filepath' does not exist");
    } else {
        return file_get_contents($filePath);
    }
}
