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
        [$pathToFirstFileToCompare,
        $pathToSecondFileToCompare,
        $pathToFileWithCorrectAnswer,
        $format] = $args;

        $pathToFirstFileToCompare = $this->getFilePath($pathToFirstFileToCompare);
        $pathToSecondFileToCompare = $this->getFilePath($pathToSecondFileToCompare);

        $correctAnswer = file_get_contents($this->getFilePath($pathToFileWithCorrectAnswer));
        $result = generateDiff($pathToFirstFileToCompare, $pathToSecondFileToCompare, $format);
        $this->assertEquals($correctAnswer, $result);
    }
    
    public function testProvider()
    {
        return [
            "prettyYamlDiff" => [
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
