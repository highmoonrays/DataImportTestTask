<?php

declare(strict_types=1);

namespace App\Tests\Service\Tool;

use App\Service\Tool\MatrixToAssociativeArrayTransformer;
use PHPUnit\Framework\TestCase;

class MatrixToAssociativeArrayTransformerTest extends TestCase
{
    /**
     * @var MatrixToAssociativeArrayTransformer
     */
    private $transformer;

    public function setUp(): void
    {
        parent::setUp();

        $this->transformer = new MatrixToAssociativeArrayTransformer();
    }

    /**
     * @param $matrixToTransform
     * @param $resultArray
     * @dataProvider provideMatrixToTransform
     */
    public function testTransformArrayToAssociative($matrixToTransform, $resultArray): void
    {
        $matrix = $matrixToTransform;
        $expectedResultArray = $this->transformer->transformArrayToAssociative($matrix);
        $this->assertSame($expectedResultArray, $resultArray);
    }

    /**
     * @return array
     */
    public function provideMatrixToTransform(): array
    {
        return[
            [
                [['key1', 'key2', 'key3'], ['value1', 'value2', 'value3']],
               [['key1' => 'value1', 'key2' => 'value2', 'key3' => 'value3']]
            ],
            [
                [['key1', 'key2', 'key3'], ['value1', 'value2', 'value3'], ['value1', 'value2', 'value3']],
                [['key1' => 'value1', 'key2' => 'value2', 'key3' => 'value3'], ['key1' => 'value1', 'key2' => 'value2', 'key3' => 'value3']]
            ]
        ];
    }

}