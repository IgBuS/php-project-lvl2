<?php

namespace Biserg\Gendiff\Runner;

use Docopt;

use function Biserg\Gendiff\Generator\generateDiff;

function run($filePath1, $filePath2, $format)
{
    $result = generateDiff($filePath1, $filePath2, $format);
    echo $result;
}
