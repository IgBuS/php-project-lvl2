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
        if (!array_key_exists($key, $dataBefore)) {
            return ['key' => $key, 'type' => 'added', 'value' => $dataAfter[$key]];
        }
        if (!array_key_exists($key, $dataAfter)) {
            return ['key' => $key, 'type' => 'deleted', 'value' => $dataBefore[$key]];
        }
        if (is_object($dataBefore[$key]) && is_object($dataAfter[$key])) {
            return ['key' => $key, 'type' => 'nested', 'children' => buildDiff($dataBefore[$key], $dataAfter[$key])];
        }
        if ($dataBefore[$key] === $dataAfter[$key]) {
            return ['key' => $key, 'type' => 'unchanged', 'value' => $dataBefore[$key]];
        }
        return ['key' => $key, 'type' => 'changed', 'oldValue' => $dataBefore[$key], 'newValue' => $dataAfter[$key]];
    }, $sortedKeys);
    return array_values($diff);
}
