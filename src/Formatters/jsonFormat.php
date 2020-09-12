<?php

namespace Gendiff\Formatters\JsonFormat;

function getOutputInJsonFormat($diff)
{
    return json_encode($diff);
}
