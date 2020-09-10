<?php

namespace Gendiff\generator;

use Funct\Collection;

function generateDiff($filePath1, $filePath2)
{
    $rawDataBefore = file_get_contents($filePath1);
    $rawDataAfter = file_get_contents($filePath2);
    $parsedDataBefore = json_decode($rawDataBefore, true);
    $parsedDataAfter = json_decode($rawDataAfter, true);

    $keys = Collection\union(array_keys($parsedDataBefore), array_keys($parsedDataAfter));
    $ast = array_reduce($keys, function ($acc, $key) use ($parsedDataBefore, $parsedDataAfter) {
        $acc[] = getTypes($key, $parsedDataBefore, $parsedDataAfter);
        return $acc;
    });

    $resultToPrint = array_reduce($ast, function ($acc, $item) {
        switch ($item['type']) {
            case 'unchanged':
                $value = boolToString($item['value']);
                $acc[] = ['sort' => $item['key'], 'result' => "    {$item['key']}: {$value}"];
                break;
            case 'changed':
                $oldValue = boolToString($item['oldValue']);
                $newValue = boolToString($item['newValue']);
                $acc[] = ['sort' => $item['key'], 'result' => "  - {$item['key']}: {$item['oldValue']}"];
                $acc[] = ['sort' => $item['key'], 'result' => "  + {$item['key']}: {$item['newValue']}"];
                break;
            case 'deleted':
                $value = boolToString($item['value']);
                $acc[] = ['sort' => $item['key'], 'result' => "  - {$item['key']}: {$value}"];
                break;
            case 'added':
                $value = boolToString($item['value']);
                $acc[] = ['sort' => $item['key'], 'result' => "  + {$item['key']}: {$value}"];
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
    print_r($result);
    $result = implode("\n", $result);
    return "{\n{$result}\n}";
}

function getTypes($key, $before, $after)
{
    
    if (!array_key_exists($key, $before)) {
        return ['type' => 'added', 'key' => $key, 'value' => $after[$key]];
    }
    if (!array_key_exists($key, $after)) {
        return ['type' => 'deleted', 'key' => $key, 'value' => $before[$key]];
    }
    if (is_array($before[$key]) && is_array($after[$key])) {
        return ['type' => 'parent', 'name' => $key, 'children' => generateDiff($before[$key], $after[$key])];
    }
    if ($before[$key] === $after[$key]) {
        return ['type' => 'unchanged', 'key' => $key, 'value' => $before[$key]];
    }
    if ($before[$key] !== $after[$key]) {
        return ['type' => 'changed', 'key' => $key, 'oldValue' => $before[$key], 'newValue' => $after[$key]];
    }
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
