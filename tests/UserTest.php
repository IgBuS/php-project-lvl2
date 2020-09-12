<?php

namespace Php\Gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\generator\generateDiff;

class UserTest extends TestCase
{
    public function testJson(): void
    {
        $pathToFirstFileToCompare = __DIR__ . "/fixtures/flatBefore.json";
        $pathToSecondFileToCompare = __DIR__ . "/fixtures/flatAfter.json";

        $correctAnswer = file_get_contents(__DIR__ . "/fixtures/expected/expFlatDiff");
        $this->assertEquals($correctAnswer, generateDiff($pathToFirstFileToCompare, $pathToSecondFileToCompare));
    }
    public function testYaml(): void
    {
        $pathToFirstFileToCompare = __DIR__ . "/fixtures/flatBefore.yaml";
        $pathToSecondFileToCompare = __DIR__ . "/fixtures/flatAfter.yaml";

        $correctAnswer = file_get_contents(__DIR__ . "/fixtures/expected/expFlatDiff");
        $this->assertEquals($correctAnswer, generateDiff($pathToFirstFileToCompare, $pathToSecondFileToCompare));
    }
    public function testRecursiveFiles(): void
    {
        $pathToFirstFileToCompare = __DIR__ . "/fixtures/recursiveBefore.json";
        $pathToSecondFileToCompare = __DIR__ . "/fixtures/recursiveAfter.json";

        $correctAnswer = file_get_contents(__DIR__ . "/fixtures/expected/expRecursiveDiff");
        $this->assertEquals($correctAnswer, generateDiff($pathToFirstFileToCompare, $pathToSecondFileToCompare));
    }
}
