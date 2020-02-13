<?php

declare(strict_types=1);

namespace App\tests\Service\Processor;

use App\Service\Factory\ReaderFactory;
use App\Service\Tool\MatrixToAssociativeArrayTransformer;
use App\Service\Processor\ImportProcessor;
use App\Service\Processor\ProductCreator;
use App\Service\Tool\FileExtensionFinder;
use Exception;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xml;
use PHPUnit\Framework\TestCase;

class ImportProcessorTest extends TestCase
{
    /**
     * @var ImportProcessor
     */
    private $importProcessor;

    /**
     * @var ReaderFactory
     */
    private $readerFactory;

    /**
     * @var FileExtensionFinder
     */
    private $extensionFinder;

    /**
     * @var MatrixToAssociativeArrayTransformer
     */
    private $transformer;

    public function setUp(): void
    {
        parent::setUp();
        $mockProductCreator = $this->getMockBuilder(ProductCreator::class)
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->readerFactory = new ReaderFactory();

        $this->extensionFinder = new FileExtensionFinder();

        $this->transformer = new MatrixToAssociativeArrayTransformer();

        $this->importProcessor = new ImportProcessor(
            $mockProductCreator,
            $this->readerFactory,
            $this->extensionFinder,
            $this->transformer
        );
    }

    /**
     * @throws Exception
     */
    public function testProcess(): void
    {
        $processor = $this->importProcessor;

        $this->assertSame(true, $processor->process('data/stock.xlsx'));
    }

    /**
     * @dataProvider provideInvalidData
     *
     * @param $invalidPathOrFile
     * @param $expectedMessage
     * @throws Exception
     */
    public function testExceptionCase($invalidPathOrFile, $expectedMessage): void
    {
        try {
            $this->assertSame(false, $this->importProcessor->process($invalidPathOrFile[0]));
        }
        catch (Exception $exception){
            $this->expectExceptionMessage($expectedMessage[0]);
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @return array
     */
    public function provideInvalidData(): array
    {
        return[
            [['stock2.csv'], ['File "stock2.csv" does not exist.']],
            [['data/stock2.csv'], ['Invalid data in given file!']]
        ];
    }
}
