<?php

namespace Biserg\Gendiff\tests;

use PHPUnit\Framework\TestCase;

use function Biserg\Gendiff\Generator\generateDiff;

const DIRECTORY_SEPARATOR = "/";

class UserTest extends TestCase
{
    /**
    * @dataProvider testProvider
    */

    public function testDiff(...$args): void
    {
        [$filepath1,
        $filepath2,
        $pathToResult,
        $format] = $args;

        $filepath1 = $this->getFilePath($filepath1);
        $filepath2 = $this->getFilePath($filepath2);

        $correctAnswer = file_get_contents($this->getFilePath($pathToResult));
        $result = generateDiff($filepath1, $filepath2, $format);
        $this->assertEquals($correctAnswer, $result);
    }
    
    public function testProvider()
    {
        return [
            "basicYamlDiff" => [
                "tests_fixtures_file1.yaml",
                "tests_fixtures_file2.yaml",
                "expRecursiveDiff",
                "basic"
            ],
            "plainRecursiveJson" => [
                "recursiveBefore.json",
                "recursiveAfter.json",
                "expRecursiveDiffPlain",
                "plain"
            ],
            "jsonRecursiveJson" => [
                "recursiveBefore.json",
                "recursiveAfter.json",
                "expRecursiveDiffJson",
                "json"
            ]
        ];
    }

    public function getFilePath($fileName)
    {
        $pathContainer = [
            'basisDIR' => __DIR__,
            'fixtures' => 'fixtures',
            'fileName' => $fileName
        ];
        $rowPath = implode(DIRECTORY_SEPARATOR, $pathContainer);
        $resultPath = realpath($rowPath);
        return $resultPath;
    }
}
