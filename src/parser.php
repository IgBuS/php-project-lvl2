<?php

namespace Biserg\Gendiff\Parser;

use Symfony\Component\Yaml\Yaml;

function parse($filePath, $data)
{
    $fileExt = pathinfo($filePath, PATHINFO_EXTENSION);

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

    if (array_key_exists($fileExt, $result)) {
        return $result[$fileExt]($data);
    } else {
        throw new \Exception("Extention '$fileExt' of '$filePath' file is not suported");
    }
}
