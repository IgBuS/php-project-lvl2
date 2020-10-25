<?php

namespace Biserg\Gendiff\Formatters\Basic;

function render($diff)
{
    $result = iter($diff);
    return "{\n{$result}\n}";
}

function iter($diff, $depth = 0)
{
    $mapped = array_map(function ($item) use ($depth) {
        $indent = str_repeat('    ', $depth);
        switch ($item['type']) {
            case 'unchanged':
                $value = stringify($item['value'], $depth);
                $result = "{$indent}    {$item['key']}: {$value}";
                return $result;
            case 'changed':
                $oldValue = stringify($item['oldValue'], $depth);
                $newValue = stringify($item['newValue'], $depth);
                $lines = [
                    "{$indent}  - {$item['key']}: {$oldValue}",
                    "{$indent}  + {$item['key']}: {$newValue}"
                ];
                $result = implode("\n", $lines);
                return $result;
            case 'deleted':
                $value = stringify($item['value'], $depth);
                $result = "{$indent}  - {$item['key']}: {$value}";
                return $result;
            case 'added':
                $value = stringify($item['value'], $depth);
                $result = "{$indent}  + {$item['key']}: {$value}";
                return $result;
            case 'nested':
                $children = iter($item['children'], $depth + 1);
                $result = "{$indent}    {$item['key']}: {\n{$children}\n    {$indent}}";
                return $result;
            default:
                throw new \Exception("Node {$item['type']} is not supported");
        }
    }, $diff);
    return implode("\n", $mapped);
}

function stringify($value, $depth)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    if (is_array($value)) {
        return implode("\n", $value);
    }
    if (is_object($value)) {
        $value = get_object_vars($value);
        return prepareArrayToOutput($value, $depth);
    }
    return $value;
}

function prepareArrayToOutput($array, $depth)
{
    $indent = str_repeat('    ', $depth + 1);
    $keys = array_keys($array);
    $values = array_map(function ($key) use ($array, $depth, $indent) {
        $value = stringify($array[$key], $depth + 1);
        return "{$indent}    {$key}: {$value}";
    }, $keys);
    $result = implode("\n", $values);
    return "{\n{$result}\n{$indent}}";
}
