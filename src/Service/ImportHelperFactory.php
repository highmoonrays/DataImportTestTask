<?php

declare(strict_types=1);

namespace App\Service;

use League\Csv\Reader;

class ImportHelperFactory
{
    /**
     * @var string
     */
    private $fileExtension;

    /**
     * @var ImportProductsFromCsvFile
     */
    private $productCsvFileProcessor;

    /**
     * @var ImportProductsFromCsvFile
     */
    private $productFileProcessor;

    /**
     * ImportProductsFromFileCommand constructor.
     */
    public function __construct(ImportProductsFromCsvFile $productCsvFileProcessor)
    {
        $this->productCsvFileProcessor = $productCsvFileProcessor;
    }

    /**
     * @throws \Exception
     */
    public function process($pathToProcessFile)
    {
        $fileNameParts = pathinfo($pathToProcessFile);
        $this->fileExtension = $fileNameParts['extension'];
        echo "$this->fileExtension";

        switch ($this->fileExtension) {
            case 'csv':
                $reader = Reader::createFromPath($pathToProcessFile);
                $rows = $reader->fetchAssoc();
                $this->productCsvFileProcessor->validateAndCreate($rows);
                $this->productFileProcessor = $this->productCsvFileProcessor;
                break;
        }
    }

    /**
     * @return array
     */
    public function getInvalidProducts()
    {
        return $this->productFileProcessor->getInvalidProducts();
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->fileExtension;
    }

    /**
     * @return int
     */
    public function getNumberSavedProducts()
    {
        return $this->productFileProcessor->getNumberSavedProducts();
    }

    /**
     * @return int
     */
    public function getNumberInvalidProducts()
    {
        return $this->productFileProcessor->getNumberInvalidProducts();
    }
}
