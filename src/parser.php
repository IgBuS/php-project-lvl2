<?php

namespace Biserg\Gendiff\Parser;

use Symfony\Component\Yaml\Yaml;

function parse($data)
{
    $result = [
        "json" => function ($data) {
            return json_decode($data['content']);
        },
        "yaml" => function ($data) {
            return Yaml::parse($data['content'], Yaml::PARSE_OBJECT_FOR_MAP);
        },
        "yml" => function ($data) {
            return Yaml::parse($data['content'], Yaml::PARSE_OBJECT_FOR_MAP);
        }
    ];

    if (array_key_exists($data['extention'], $result)) {
        return $result[$data['extention']]($data);
    } else {
        throw new \Exception("Extention" . $data['extention'] . "of file is not suported");
    }
}
