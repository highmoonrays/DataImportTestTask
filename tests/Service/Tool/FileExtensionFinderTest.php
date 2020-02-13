<?php

declare(strict_types=1);

namespace App\Tests\Service\Tool;

use App\Service\Tool\FileExtensionFinder;
use Exception;
use PHPUnit\Framework\TestCase;

class FileExtensionFinderTest extends TestCase
{
    /**
     * @var FileExtensionFinder
     */
    private $extensionFinder;

    public function setUp(): void
    {
        parent::setUp();

        $this->extensionFinder = new FileExtensionFinder();
    }

    public function testFindFileExtensionFromPath(): void
    {
        $this->assertSame('data', $this->extensionFinder->findFileExtensionFromPath('testfile.data'));
        $this->assertSame('', $this->extensionFinder->findFileExtensionFromPath('testfile.'));
    }

    /**
     * @throws Exception
     */
    public function testExceptionCase(): void
    {
        try {
            $this->assertSame(null, $this->extensionFinder->findFileExtensionFromPath('testfile'));
        }
        catch (Exception $exception){
            $this->expectExceptionMessage('Incorrect extension');
            throw new Exception($exception->getMessage());
        }
    }
}