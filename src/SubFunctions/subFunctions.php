<?php

namespace Gendiff\SubFunctions\SubFunctions;

function convertBoolToString($value)
{
    if ($value === true) {
        return "true";
    } elseif ($value === false) {
        return "false";
    } else {
        return $value;
    }
}
