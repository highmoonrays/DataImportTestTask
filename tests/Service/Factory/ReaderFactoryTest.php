<?php

declare(strict_types=1);

namespace App\test\Service\Factory;

use App\Service\Factory\ReaderFactory;
use PHPUnit\Framework\TestCase;

class ReaderFactoryTest extends TestCase
{
    public function testGetFileReader()
    {
        $readerFactory = new ReaderFactory();

            $this->assertIsObject($readerFactory->getFileReader('csv'));

            $this->assertIsObject($readerFactory->getFileReader('xlsx'));

            $this->assertIsObject($readerFactory->getFileReader('xml'));
    }
}