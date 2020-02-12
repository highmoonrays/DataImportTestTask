<?php

declare(strict_types=1);

namespace App\Service\Processor;

use App\Service\Factory\ReaderFactory;
use App\Service\Tool\Converter;
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
     * @var Converter
     */
    private $converter;

    /**
     * ImportProductsFromFile constructor.
     * @param ProductFileProcessor $productFileProcessor
     * @param ReaderFactory $readerFactory
     * @param FileExtensionFinder $extensionFinder
     * @param Converter $converter
     */
    public function __construct(
        ProductFileProcessor $productFileProcessor,
        ReaderFactory $readerFactory,
        FileExtensionFinder $extensionFinder,
        Converter $converter
    ) {
        $this->productFileProcessor = $productFileProcessor;
        $this->readerFactory = $readerFactory;
        $this->extensionFinder = $extensionFinder;
        $this->converter = $converter;
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
            $rowsWithKeys = $this->converter->arrayToAssociative($rows);
            $this->productFileProcessor->importProductsFromFile($rowsWithKeys);
        }

        return true;
    }
}
