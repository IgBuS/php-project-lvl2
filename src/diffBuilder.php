<?php

namespace Biserg\Gendiff\DiffBuilder;

use Funct\Collection;

function buildDiff($parsedDataBefore, $parsedDataAfter)
{
    $parsedDataBefore = get_object_vars($parsedDataBefore);
    $parsedDataAfter = get_object_vars($parsedDataAfter);
    $keys = Collection\union(array_keys($parsedDataBefore), array_keys($parsedDataAfter));
    sort($keys);
    $diff = array_map(function ($key) use ($parsedDataBefore, $parsedDataAfter) {
        $acc = getTypes($key, $parsedDataBefore, $parsedDataAfter);
        return $acc;
    }, $keys);
    return array_values($diff);
}

function getTypes($key, $before, $after)
{

    if (!array_key_exists($key, $before)) {
        return ['key' => $key, 'type' => 'added', 'value' => $after[$key]];
    }
    if (!array_key_exists($key, $after)) {
        return ['key' => $key, 'type' => 'deleted', 'value' => $before[$key]];
    }
    if (is_object($before[$key]) && is_object($after[$key])) {
        return ['key' => $key, 'type' => 'nested', 'children' => buildDiff($before[$key], $after[$key])];
    }
    if ($before[$key] === $after[$key]) {
        return ['key' => $key, 'type' => 'unchanged', 'value' => $before[$key]];
    }
    return ['key' => $key, 'type' => 'changed', 'oldValue' => $before[$key], 'newValue' => $after[$key]];
}
