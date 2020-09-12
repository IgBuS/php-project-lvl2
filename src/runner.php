<?php

namespace Gendiff\Runner;

use Docopt;

use function Gendiff\Generator\generateDiff;

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
    print_r($result);
}
