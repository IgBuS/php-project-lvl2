<?php

namespace Biserg\Gendiff\Generator;

use function Biserg\Gendiff\Parser\parse;
use function Biserg\Gendiff\DiffBuilder\buildDiff;
use function Biserg\Gendiff\Formatters\Formater\format;

const AVALIABLE_FORMATS = ['json', 'yaml', 'yml', ];

function generateDiff($filePath1, $filePath2, $format = 'basic')
{
    if (file_exists($filePath1) && file_exists($filePath1)) {
        $rawDataBefore = file_get_contents($filePath1);
        $rawDataAfter = file_get_contents($filePath2);
    } else {
        throw new Exception("One of files or both of them do not exist");
    }

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
