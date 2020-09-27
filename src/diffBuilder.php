<?php

namespace Biserg\Gendiff\DiffBuilder;

use Funct\Collection;

function buildDiff($parsedDataBefore, $parsedDataAfter)
{
    $parsedDataBefore = get_object_vars($parsedDataBefore);
    $parsedDataAfter = get_object_vars($parsedDataAfter);
    $keys = Collection\union(array_keys($parsedDataBefore), array_keys($parsedDataAfter));
    $diff = array_reduce($keys, function ($acc, $key) use ($parsedDataBefore, $parsedDataAfter) {
        $acc[] = getTypes($key, $parsedDataBefore, $parsedDataAfter);
        return $acc;
    });
    return $diff;
}

function getTypes($key, $before, $after)
{

    if (!array_key_exists($key, $before)) {
        return ['type' => 'added', 'key' => $key, 'value' => $after[$key]];
    }
    if (!array_key_exists($key, $after)) {
        return ['type' => 'deleted', 'key' => $key, 'value' => $before[$key]];
    }
    if (is_object($before[$key]) && is_object($after[$key])) {
        return ['type' => 'parent', 'key' => $key, 'children' => buildDiff($before[$key], $after[$key])];
    }

    if ($before[$key] === $after[$key]) {
        return ['type' => 'unchanged', 'key' => $key, 'value' => $before[$key]];
    } else {
        return ['type' => 'changed', 'key' => $key, 'oldValue' => $before[$key], 'newValue' => $after[$key]];
    }
}
