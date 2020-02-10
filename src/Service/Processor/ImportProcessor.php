<?php

declare(strict_types=1);

namespace App\Service\Processor;

use App\Service\Factory\ReaderFactory;
use App\Service\ImportProductsFromFile;

class ImportProcessor
{
    /**
     * @var ImportProductsFromFile
     */
    private $productFileProcessor;

    /**
     * @var ReaderFactory
     */
    private $readerFactory;

    /**
     * ImportProductsFromFileCommand constructor.
     * @param ImportProductsFromFile $productCsvFileProcessor
     * @param ReaderFactory $readerFactory
     */
    public function __construct(
        ImportProductsFromFile $productCsvFileProcessor,
        ReaderFactory $readerFactory
    ) {
        $this->productFileProcessor = $productCsvFileProcessor;
        $this->readerFactory = $readerFactory;
    }

    /**
     * @param $pathToProcessFile
     *
     * @return bool
     *
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
            $headers = $rows[0];
            unset($rows[0]);
            $rowsWithKeys = [];

            foreach ($rows as $row) {
                $newRow = [];

                foreach ($headers as $k => $key) {
                    $newRow[$key] = $row[$k];
                }
                $rowsWithKeys[] = $newRow;
            }
            $this->productFileProcessor->import($rowsWithKeys);
        }

        return true;
    }
}
