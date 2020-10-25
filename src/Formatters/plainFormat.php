<?php

namespace Biserg\Gendiff\Formatters\Plain;

use Funct\Collection;

function render($diff)
{
    $mapped = iter($diff);
    $flattened = Collection\flattenAll($mapped);
    return implode("\n", $flattened);
}

function iter($diff, $ancestry = "")
{
    $mapped = array_map(function ($item) use ($ancestry) {
        switch ($item['type']) {
            case 'changed':
                $oldValue = stringify($item['oldValue']);
                $newValue = stringify($item['newValue']);
                $result = "Property '{$ancestry}{$item["key"]}' was updated. From {$oldValue} to {$newValue}";
                return $result;
            case 'deleted':
                $value = stringify($item['value']);
                $result = "Property '{$ancestry}{$item["key"]}' was removed";
                return $result;
            case 'added':
                $value = stringify($item['value']);
                $result = "Property '{$ancestry}{$item["key"]}' was added with value: {$value}";
                return $result;
            case 'nested':
                $children = iter($item['children'], "{$ancestry}{$item['key']}.");
                $result = $children;
                return $result;
            case 'unchanged':
                return [];
            default:
                throw new \Exception("Node {$item['type']} is not supported");
        }
    }, $diff);
    return $mapped;
}

function stringify($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    if (is_array($value) || is_object($value)) {
        return '[complex value]';
    }
    return "'{$value}'";
}
