<?php

namespace Biserg\Gendiff\Parser;

use Symfony\Component\Yaml\Yaml;

function parse($data, $dataType)
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

    if (!array_key_exists($dataType, $result)) {
        throw new \Exception("File type {$dataType} is not suported");
    }
    return $result[$dataType]($data);
}
