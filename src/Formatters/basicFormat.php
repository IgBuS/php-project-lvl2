<?php

namespace Biserg\Gendiff\Formatters\BasicFormat;

function getOutputInBasicFormat($diff)
{
    $result = getOutput($diff);
    return "{\n{$result}\n}";
}

function getOutput($diff)
{
    function iter($diff, $depth = 0)
    {
        $indent = str_repeat('    ', $depth);
        $resultToPrint = array_map(function ($item) use ($indent, $depth) {
            switch ($item['type']) {
                case 'unchanged':
                    $value = stringify($item['value'], $depth);
                    $acc = "{$indent}    {$item['key']}: {$value}";
                    break;
                case 'changed':
                    $oldValue = stringify($item['oldValue'], $depth);
                    $newValue = stringify($item['newValue'], $depth);
                    $acc = "{$indent}  - {$item['key']}: {$oldValue}\n{$indent}  + {$item['key']}: {$newValue}";
                    break;
                case 'deleted':
                    $value = stringify($item['value'], $depth);
                    $acc = "{$indent}  - {$item['key']}: {$value}";
                    break;
                case 'added':
                    $value = stringify($item['value'], $depth);
                    $acc = "{$indent}  + {$item['key']}: {$value}";
                    break;
                case 'nested':
                    $children = iter($item['children'], $depth + 1);
                    $acc = "{$indent}    {$item['key']}: {\n{$children}\n    {$indent}}";
                    break;
            }
            return $acc;
        }, $diff);
        $resultToPrint = implode("\n", $resultToPrint);
        return "{$resultToPrint}";
    }
    return iter($diff);
}

function stringify($value, $depth)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
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
