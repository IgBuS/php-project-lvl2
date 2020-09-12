<?php

namespace Gendiff\shapers\basicFormat;

function getOutput($diff, $depth = 0)
{
    $indent = str_repeat('    ', $depth);
    $resultToPrint = array_reduce($diff, function ($acc, $item) use ($indent, $depth) {
        switch ($item['type']) {
            case 'unchanged':
                $value = valueToOutput($item['value'], $depth);
                $acc[] = ['sort' => $item['key'], 'result' => "{$indent}    {$item['key']}: {$value}"];
                break;
            case 'changed':
                $oldValue = valueToOutput($item['oldValue'], $depth);
                $newValue = valueToOutput($item['newValue'], $depth);
                $acc[] = ['sort' => $item['key'], 'result' => "{$indent}  - {$item['key']}: {$oldValue}"];
                $acc[] = ['sort' => $item['key'], 'result' => "{$indent}  + {$item['key']}: {$newValue}"];
                break;
            case 'deleted':
                $value = valueToOutput($item['value'], $depth);
                $acc[] = ['sort' => $item['key'], 'result' => "{$indent}  - {$item['key']}: {$value}"];
                break;
            case 'added':
                $value = valueToOutput($item['value'], $depth);
                $acc[] = ['sort' => $item['key'], 'result' => "{$indent}  + {$item['key']}: {$value}"];
                break;
            case 'parent':
                $children = getOutput($item['children'], $depth + 1);
                $acc[] = ['sort' => $item['key'], 'result' => "{$indent}    {$item['key']}: {\n{$children}\n    {$indent}}"];
                break;
        }
        return $acc;
    }, []);
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

function valueToOutput($value, $depth)
{
    if (is_bool($value)) {
        return boolToString($value);
    }
    if (is_array($value)) {
        return arrayToOutput($value, $depth);
    }
    return $value;
}

function arrayToOutput($array, $depth)
{
    $indent = str_repeat('    ', $depth + 1);
    $keys = array_keys($array);
    $values = array_reduce($keys, function ($acc, $key) use ($array, $indent, $depth) {
        $value = valueToOutput($array[$key], $depth + 1);
        $acc[] = "{$indent}    {$key}: {$value}";
        return $acc;
    });
    $result = implode(PHP_EOL, $values);
    return "{\n{$result}\n{$indent}}";
}

function boolToString($value)
{
    if ($value === true) {
        return "true";
    } elseif ($value === false) {
        return "false";
    } else {
        return $value;
    }
}
