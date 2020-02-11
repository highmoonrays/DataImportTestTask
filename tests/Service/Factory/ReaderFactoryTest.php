<?php

declare(strict_types=1);

namespace App\tests\Service\Factory;

use App\Service\Factory\ReaderFactory;
use PHPUnit\Framework\TestCase;

class ReaderFactoryTest extends TestCase
{
    /**
     * @var ReaderFactory
     */
    private $readerFactory;

    public function setUp()
    {
        $this->readerFactory = new ReaderFactory();
    }

    public function testGetFileReader()
    {
            $this->assertIsObject($this->readerFactory->getFileReader('csv'));
            $this->assertIsObject($this->readerFactory->getFileReader('xlsx'));
            $this->assertIsObject($this->readerFactory->getFileReader('xml'));
    }
}