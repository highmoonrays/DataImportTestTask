<?php

declare(strict_types=1);

namespace App\tests\Service\Processor;

use App\Service\Factory\ReaderFactory;
use App\Service\Processor\ImportProcessor;
use App\Service\Processor\ProductFileProcessor;
use App\Service\Tool\FileExtensionFinder;
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

    public function setUp()
    {
        $mockProductFileProcessor =
            $this
                ->getMockBuilder(ProductFileProcessor::class)
                ->disableOriginalConstructor()
                ->getMock()
        ;

        $mockReaderFactory =
            $this
                ->getMockBuilder(ReaderFactory::class)
                ->setMethods(['getFileReader'])
                ->getMock()
        ;

        $mockReaderFactory
            ->expects($this->once())
            ->method('getFileReader')
            ->willReturn(new Xlsx())
        ;

        $mockFileExtensionFinder =
            $this
                ->getMockBuilder(FileExtensionFinder::class)
                ->getMock();

        $this->importProcessor = new ImportProcessor($mockProductFileProcessor, $mockReaderFactory, $mockFileExtensionFinder);
    }

    /**
     * @throws \Exception
     */
    public function testProcess()
    {
        $processor = $this->importProcessor;

        $this->assertSame(true, $processor->process('data/stock.xlsx'));

    }
}
