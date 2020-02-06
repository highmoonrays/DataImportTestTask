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
     * @var string
     */
    private $pathToProcessFile;

    /**
     * ImportProductsFromFileCommand constructor.
     */
    public function __construct(ImportProductsFromCsvFile $productCsvFileProcessor)
    {
        $this->productCsvFileProcessor = $productCsvFileProcessor;
        if ('csv' === $this->getFileExtension()) {
            $this->productFileProcessor = $productCsvFileProcessor;
        }
    }

    /**
     * @param $pathToProcessFile
     * @throws \Exception
     */
    public function useProcessor($pathToProcessFile)
    {
        $fileNameParts = pathinfo($pathToProcessFile);
        $this->fileExtension = $fileNameParts['extension'];
        $reader = Reader::createFromPath($pathToProcessFile);
        $rows = $reader->fetchAssoc();
        $this->productFileProcessor->validateAndCreate($rows);
    }

    /**
     * @return string
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * @return array
     */
    public function getInvalidProducts()
    {
        return $this->productFileProcessor->getInvalidProducts();
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
