<?php

namespace Biserg\Gendiff\Formatters\Json;

function render($diff)
{
    return json_encode($diff, JSON_THROW_ON_ERROR);
}
