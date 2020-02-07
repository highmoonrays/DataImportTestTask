<?php

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
    private $helperFactory;

    /**
     * ImportProductsFromFileCommand constructor.
     */
    public function __construct(
        ImportProductsFromFile $productCsvFileProcessor,
        ReaderFactory $helperFactory
    ) {
        $this->productFileProcessor = $productCsvFileProcessor;
        $this->helperFactory = $helperFactory;
    }

    /**
     * @param $pathToProcessFile
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function process($pathToProcessFile)
    {
        $fileNameParts = pathinfo($pathToProcessFile);
        $fileExtension = $fileNameParts['extension'];
        $reader = $this->helperFactory->getFileReader($fileExtension);
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
