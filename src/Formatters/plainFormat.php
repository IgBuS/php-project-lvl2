<?php

namespace Biserg\Gendiff\Formatters\Plain;

use Funct\Collection;

function render($diff)
{
    $mapped = iter($diff);
    $flattened = Collection\flattenAll($mapped);
    $compacted = Collection\compact($flattened);
    return implode("\n", $compacted);
}

function iter($diff, $level = null)
{
    $mapped = array_map(function ($item) use ($level) {
        $level === null ? $level = "{$item['key']}" : $level = "{$level}.{$item['key']}";
        switch ($item['type']) {
            case 'changed':
                $oldValue = stringify($item['oldValue']);
                $newValue = stringify($item['newValue']);
                $acc = "Property '{$level}' was updated. From {$oldValue} to {$newValue}";
                return $acc;
            case 'deleted':
                $value = stringify($item['value']);
                $acc = "Property '{$level}' was removed";
                return $acc;
            case 'added':
                $value = stringify($item['value']);
                $acc = "Property '{$level}' was added with value: {$value}";
                return $acc;
            case 'nested':
                $children = iter($item['children'], $level);
                $acc = $children;
                return $acc;
            default:
                break;
        }
    }, $diff);
    return $mapped;
}

function stringify($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_array($value) || is_object($value)) {
        return '[complex value]';
    }
    return "'{$value}'";
}
