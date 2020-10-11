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
        [$filepath1,
        $filepath2,
        $pathToResult,
        $format] = $args;

        $filepath1 = $this->getFilePath($filepath1);
        $filepath2 = $this->getFilePath($filepath2);
        $pathToResult = $this->getFilePath($pathToResult);

        $result = generateDiff($filepath1, $filepath2, $format);
        $this->assertStringEqualsFile($pathToResult, $result);
    }
    
    public function testProvider()
    {
        return [
            "basicYamlDiff" => [
                "file1.yaml",
                "file2.yaml",
                "diff.stylish",
                "basic"
            ],
            "plainRecursiveJson" => [
                "file1.json",
                "file2.json",
                "diff.plain",
                "plain"
            ],
            "jsonRecursiveJson" => [
                "file1.json",
                "file2.json",
                "diff.json",
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
