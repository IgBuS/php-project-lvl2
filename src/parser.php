<?php

namespace Biserg\Gendiff\Parser;

use Symfony\Component\Yaml\Yaml;

function parse($data, $dataType)
{
    $result = [
        "json" => fn($data) => json_decode($data),
        "yaml" => fn($data) => Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP),
        "yml" => fn($data) => Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP)
    ];

    if (!array_key_exists($dataType, $result)) {
        throw new \Exception("File type {$dataType} is not suported");
    }
    return $result[$dataType]($data);
}
