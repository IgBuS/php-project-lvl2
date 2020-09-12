<?php

namespace Php\Gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\generator\generateDiff;

class UserTest extends TestCase
{
    public function testJson(): void
    {
        $pathToFirstFileToCompare = __DIR__ . "/fixtures/file1.json";
        $pathToSecondFileToCompare = __DIR__ . "/fixtures/file2.json";

        $correctAnswer = file_get_contents(__DIR__ . "/fixtures/expected/TwoJson");
        $this->assertEquals($correctAnswer, generateDiff($pathToFirstFileToCompare, $pathToSecondFileToCompare));
    }
    public function testYaml(): void
    {
        $pathToFirstFileToCompare = __DIR__ . "/fixtures/file1.yaml";
        $pathToSecondFileToCompare = __DIR__ . "/fixtures/file2.yaml";

        $correctAnswer = file_get_contents(__DIR__ . "/fixtures/expected/TwoJson");
        $this->assertEquals($correctAnswer, generateDiff($pathToFirstFileToCompare, $pathToSecondFileToCompare));
    }
}
