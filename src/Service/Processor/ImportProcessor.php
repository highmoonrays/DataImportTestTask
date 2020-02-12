<?php

declare(strict_types=1);

namespace App\Service\Processor;

use App\Service\Factory\ReaderFactory;
use App\Service\Tool\FileExtensionFinder;
use Exception;

class ImportProcessor
{
    /**
     * @var productFileProcessor
     */
    private $productFileProcessor;

    /**
     * @var ReaderFactory
     */
    private $readerFactory;

    /**
     * @var FileExtensionFinder
     */
    private $extensionFinder;

    /**
     * ImportProductsFromFile constructor.
     * @param ProductFileProcessor $productFileProcessor
     * @param ReaderFactory $readerFactory
     * @param FileExtensionFinder $extensionFinder
     */
    public function __construct(
        ProductFileProcessor $productFileProcessor,
        ReaderFactory $readerFactory,
        FileExtensionFinder $extensionFinder
    ) {
        $this->productFileProcessor = $productFileProcessor;
        $this->readerFactory = $readerFactory;
        $this->extensionFinder = $extensionFinder;
    }

    /**
     * @param $pathToProcessFile
     *
     * @return bool
     * @throws Exception
     */
    public function process($pathToProcessFile): bool
    {
        $fileExtension = $this->extensionFinder->findFileExtensionFromPath($pathToProcessFile);
        $reader = $this->readerFactory->getFileReader($fileExtension);

        if (null === $reader) {
            return false;
        } else {
            $spreadSheet = $reader->load($pathToProcessFile);
            $rows = $spreadSheet->getActiveSheet()->toArray();
            $this->productFileProcessor->importProductsFromFile($rows);
        }

        return true;
    }
}
