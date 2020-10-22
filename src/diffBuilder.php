<?php

namespace Biserg\Gendiff\DiffBuilder;

use Funct\Collection;

function buildDiff($dataBefore, $dataAfter)
{
    $dataBefore = get_object_vars($dataBefore);
    $dataAfter = get_object_vars($dataAfter);
    $keys = Collection\union(array_keys($dataBefore), array_keys($dataAfter));
    $sortedKeys = Collection\sortBy($keys, fn($key) => $key);
    $diff = array_map(function ($key) use ($dataBefore, $dataAfter) {
        $acc = getTypes($key, $dataBefore, $dataAfter);
        return $acc;
    }, $sortedKeys);
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
