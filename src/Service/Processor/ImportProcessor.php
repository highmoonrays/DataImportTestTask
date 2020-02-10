<?php

declare(strict_types=1);

namespace App\Service\Processor;

use App\Service\Factory\ReaderFactory;

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
     * ImportProductsFromFileCommand constructor.
     * @param ProductFileProcessor $productCsvFileProcessor
     * @param ReaderFactory $readerFactory
     */
    public function __construct(
        ProductFileProcessor $productCsvFileProcessor,
        ReaderFactory $readerFactory
    ) {
        $this->productFileProcessor = $productCsvFileProcessor;
        $this->readerFactory = $readerFactory;
    }

    /**
     * @param $pathToProcessFile
     *
     * @return bool
     * @throws \Exception
     */
    public function process($pathToProcessFile): bool
    {
        $fileNameParts = pathinfo($pathToProcessFile);
        $fileExtension = $fileNameParts['extension'];
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
