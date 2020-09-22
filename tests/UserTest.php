<?php

namespace Biserg\Gendiff\tests;

use PHPUnit\Framework\TestCase;

use function Biserg\Gendiff\Generator\generateDiff;

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

        $correctAnswer = file_get_contents($this->getFilePath($pathToFileWithCorrectAnswer, 'answer'));
        $result = generateDiff($pathToFirstFileToCompare, $pathToSecondFileToCompare, $format);
        $this->assertEquals($correctAnswer, $result);
    }
    
    public function testProvider()
    {
        return [
            "prettyJsonDiff" => [
                "flatBefore.json",
                "flatAfter.json",
                "expFlatDiff",
                "basic"
            ],
            "prettyYamlDiff" => [
                "flatBefore.yaml",
                "flatAfter.yaml",
                "expFlatDiff",
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

    public function getFilePath($fileName, $fileType = 'example')
    {
        if ($fileType == 'example') {
            return __DIR__ . "/fixtures/{$fileName}";
        } else {
            return __DIR__ . "/fixtures/expected/{$fileName}";
        }
    }
}
