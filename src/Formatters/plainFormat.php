<?php

namespace Biserg\Gendiff\Formatters\Plain;

function render($diff)
{
    return iter($diff);
}

function iter($diff, $level = null)
{
    $resultToPrint = array_reduce($diff, function ($acc, $item) use ($level) {
        $level === null ? $level = "{$item['key']}" : $level = "{$level}.{$item['key']}";
        switch ($item['type']) {
            case 'changed':
                $oldValue = transformValueToOutputFormat($item['oldValue']);
                $newValue = transformValueToOutputFormat($item['newValue']);
                $acc[] = "Property '{$level}' was updated. From {$oldValue} to {$newValue}";
                break;
            case 'deleted':
                $value = transformValueToOutputFormat($item['value']);
                $acc[] = "Property '{$level}' was removed";
                break;
            case 'added':
                $value = transformValueToOutputFormat($item['value']);
                $acc[] = "Property '{$level}' was added with value: {$value}";
                break;
            case 'nested':
                $children = iter($item['children'], $level);
                $acc[] = $children;
                break;
        }
        return $acc;
    }, []);
    return prepareToOutput($resultToPrint);
}

function prepareToOutput($resultToPrint)
{
    sort($resultToPrint);
    $result = implode("\n", $resultToPrint);
    return $result;
}

function transformValueToOutputFormat($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_array($value) || is_object($value)) {
        return '[complex value]';
    }
    return "'{$value}'";
}
