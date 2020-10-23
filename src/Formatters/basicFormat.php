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
                $acc = "{$indent}    {$item['key']}: {$value}";
                return $acc;
            case 'changed':
                $oldValue = stringify($item['oldValue'], $depth);
                $newValue = stringify($item['newValue'], $depth);
                $lines = [
                    "{$indent}  - {$item['key']}: {$oldValue}",
                    "{$indent}  + {$item['key']}: {$newValue}"
                ];
                $acc = implode("\n", $lines);
                return $acc;
            case 'deleted':
                $value = stringify($item['value'], $depth);
                $acc = "{$indent}  - {$item['key']}: {$value}";
                return $acc;
            case 'added':
                $value = stringify($item['value'], $depth);
                $acc = "{$indent}  + {$item['key']}: {$value}";
                return $acc;
            case 'nested':
                $children = iter($item['children'], $depth + 1);
                $acc = "{$indent}    {$item['key']}: {\n{$children}\n    {$indent}}";
                return $acc;
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
        return prepareArrayToOutput($value, $depth);
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
    $values = array_reduce($keys, function ($acc, $key) use ($array, $indent, $depth) {
        $value = stringify($array[$key], $depth + 1);
        $acc[] = "{$indent}    {$key}: {$value}";
        return $acc;
    });
    $result = implode(PHP_EOL, $values);
    return "{\n{$result}\n{$indent}}";
}
