<?php

namespace Php\Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use Php\Package\User;

class UserTest extends TestCase
{
    public function testGendiff(): void
    {
        $pathToFirstFileToCompare = __DIR__ . "/fixtures/file1.json";
        $pathToSecondFileToCompare = __DIR__ . "/fixtures/file2.json";

        $correctAnswer = file_get_contents(__DIR__ . "fixtures/expected/TwoJson")
        $this->assertEquals($name, $user->getName());
        $this->assertEquals(collect($children), $user->getChildren());
    }
}