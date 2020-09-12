<?php

namespace Gendiff\parser;

use Symfony\Component\Yaml\Yaml;

function parse($data, $fileType)
{
    $result = [
        "json" => function ($data) {
            return json_decode($data, true);
        },
        "yaml" => function ($data) {
            return Yaml::parse($data);
        }
    ];
    return $result[$fileType]($data);
}
