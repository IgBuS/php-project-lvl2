<?php

namespace Biserg\Gendiff\Formatters\BasicFormat;

use function Biserg\Gendiff\SubFunctions\SubFunctions\convertBoolToString;

function getOutputInBasicFormat($diff, $depth = 0)
{
    $result = getOutput($diff);
    return "{\n{$result}\n}";
}

function getOutput($diff, $depth = 0)
{
    $indent = str_repeat('    ', $depth);
    $resultToPrint = array_reduce($diff, function ($acc, $item) use ($indent, $depth) {
        switch ($item['type']) {
            case 'unchanged':
                $value = prepareValueToOutput($item['value'], $depth);
                $acc[] = ['sort' => $item['key'], 'result' => "{$indent}    {$item['key']}: {$value}"];
                break;
            case 'changed':
                $oldValue = prepareValueToOutput($item['oldValue'], $depth);
                $newValue = prepareValueToOutput($item['newValue'], $depth);
                $acc[] = ['sort' => $item['key'], 'result' => "{$indent}  - {$item['key']}: {$oldValue}"];
                $acc[] = ['sort' => $item['key'], 'result' => "{$indent}  + {$item['key']}: {$newValue}"];
                break;
            case 'deleted':
                $value = prepareValueToOutput($item['value'], $depth);
                $acc[] = ['sort' => $item['key'], 'result' => "{$indent}  - {$item['key']}: {$value}"];
                break;
            case 'added':
                $value = prepareValueToOutput($item['value'], $depth);
                $acc[] = ['sort' => $item['key'], 'result' => "{$indent}  + {$item['key']}: {$value}"];
                break;
            case 'parent':
                $children = getOutput($item['children'], $depth + 1);
                $acc[] = ['sort' => $item['key'],
                'result' => "{$indent}    {$item['key']}: {\n{$children}\n    {$indent}}"];
                break;
        }
        return $acc;
    }, []);
    return prepareToOutput($resultToPrint);
}

function prepareToOutput($resultToPrint)
{
    usort($resultToPrint, function ($a, $b) {
        return strcmp($a["sort"], $b["sort"]);
    });
    $result = array_reduce($resultToPrint, function ($acc, $item) {
        $acc[] = $item['result'];
        return $acc;
    }, []);
    $result = implode("\n", $result);
    return "{$result}";
}

function prepareValueToOutput($value, $depth)
{
    if (is_bool($value)) {
        return convertBoolToString($value);
    }
    if (is_array($value)) {
        return prepareArrayToOutput($value, $depth);
    }
    return $value;
}

function prepareArrayToOutput($array, $depth)
{
    $indent = str_repeat('    ', $depth + 1);
    $keys = array_keys($array);
    $values = array_reduce($keys, function ($acc, $key) use ($array, $indent, $depth) {
        $value = prepareValueToOutput($array[$key], $depth + 1);
        $acc[] = "{$indent}    {$key}: {$value}";
        return $acc;
    });
    $result = implode(PHP_EOL, $values);
    return "{\n{$result}\n{$indent}}";
}
