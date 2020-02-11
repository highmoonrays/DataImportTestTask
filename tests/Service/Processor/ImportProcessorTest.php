<?php

declare(strict_types=1);

namespace App\tests\Service\Processor;

use App\Service\Factory\ReaderFactory;
use App\Service\Processor\ImportProcessor;
use App\Service\Processor\ProductFileProcessor;
use PHPUnit\Framework\TestCase;

class ImportProcessorTest extends TestCase
{
    /**
     * @var ImportProcessor
     */
    private $importProcessor;

    public function setUp()
    {
        $mockProductFileProcessor = $this->getMockBuilder(ProductFileProcessor::class)->disableOriginalConstructor()->getMock();

        $mockReaderFactory = $this->getMockBuilder(ReaderFactory::class)->setMethods(['getFileReader'])->getMock();
        $mockReaderFactory->expects($this->once())->method('getFileReader')->with('csv');
        $this->importProcessor = new ImportProcessor($mockProductFileProcessor, $mockReaderFactory);

    }

    /**
     * @throws \Exception
     */
    public function testProcess()
    {
        $processor = $this->importProcessor;

        $this->assertSame(false, $processor->process('data/stock.csv'));
    }
}