<?php

namespace Gendiff\runner;

use Docopt;

use function Gendiff\generator\generateDiff;

function run()
{
    $doc = <<<DOC
	Generate diff
	Usage:
	  gendiff (-h|--help)
	  gendiff [--format <fmt>] <firstFile> <secondFile>
	Options:
	  -h --help                     Show this screen
	  --format <fmt>                Report format [default: pretty]
	DOC;

    $args = Docopt::handle($doc);
    $filePath1 = $args['<firstFile>'];
    $filePath2 = $args['<secondFile>'];
    $format = $args['--format'];
        
    $result = generateDiff($filePath1, $filePath2);

    $resultToPrint = array_reduce($result, function ($acc, $item) {
        switch ($item['type']) {
            case 'unchanged':
                $acc[] = ['sort' => $item['key'], 'result' => "  {$item['key']}: {$item['value']}"];
                break;
            case 'changed':
                $acc[] = ['sort' => $item['key'], 'result' => "- {$item['key']}: {$item['oldValue']}"];
                $acc[] = ['sort' => $item['key'], 'result' => "+ {$item['key']}: {$item['newValue']}"];
                break;
            case 'deleted':
                $acc[] = ['sort' => $item['key'], 'result' => "- {$item['key']}: {$item['value']}"];
                break;
            case 'added':
                $acc[] = ['sort' => $item['key'], 'result' => "+ {$item['key']}: {$item['value']}"];
                break;
        }
        return $acc;
    }, []);
    usort($resultToPrint, function ($a, $b) {
        return strcmp($a["sort"], $b["sort"]);
    });
    foreach ($resultToPrint as $result) {
        echo "{$result['result']} \n";
    };
}
