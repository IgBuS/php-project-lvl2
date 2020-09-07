<?php

namespace Gendiff\generator;

use Funct\Collection;

function generateDiff($filePath1, $filePath2)
{
    $rawDataBefore = file_get_contents($filePath1);
    $rawDataAfter = file_get_contents($filePath2);
    $parsedDataBefore = json_decode($rawDataBefore, true);
    $parsedDataAfter = json_decode($rawDataAfter, true);

    $keys = Collection\union(array_keys($parsedDataBefore), array_keys($parsedDataAfter));
    $ast = array_reduce($keys, function ($acc, $key) use ($parsedDataBefore, $parsedDataAfter) {
        $acc[] = getTypes($key, $parsedDataBefore, $parsedDataAfter);
        return $acc;
    });
    return $ast;
}

function getTypes($key, $before, $after)
{
    
    if (!array_key_exists($key, $before)) {
        return ['type' => 'added', 'key' => $key, 'value' => $after[$key]];
    }
    if (!array_key_exists($key, $after)) {
        return ['type' => 'deleted', 'key' => $key, 'value' => $before[$key]];
    }
    if (is_array($before[$key]) && is_array($after[$key])) {
        return ['type' => 'parent', 'name' => $key, 'children' => generateDiff($before[$key], $after[$key])];
    }
    if ($before[$key] === $after[$key]) {
        return ['type' => 'unchanged', 'key' => $key, 'value' => $before[$key]];
    }
    if ($before[$key] !== $after[$key]) {
        return ['type' => 'changed', 'key' => $key, 'oldValue' => $before[$key], 'newValue' => $after[$key]];
    }
}
