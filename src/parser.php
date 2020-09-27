<?php

namespace Biserg\Gendiff\Parser;

use Symfony\Component\Yaml\Yaml;

function parse($data, $fileType)
{
    $result = [
        "json" => function ($data) {
            return json_decode($data);
        },
        "yaml" => function ($data) {
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        },
        "yml" => function ($data) {
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        }
    ];
    return $result[$fileType]($data);
}
