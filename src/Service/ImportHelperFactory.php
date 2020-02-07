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
     * @var array
     */
    private $report = [];

    /**
     * @var string
     */
    public const REPORT_INVALID_PRODUCTS = 'invalid_products';

    /**
     * @var string
     */
    public const REPORT_NUMBER_SAVED_PRODUCTS = 'number_saved_products';

    /**
     * ImportProductsFromFileCommand constructor.
     */
    public function __construct(ImportProductsFromCsvFile $productCsvFileProcessor)
    {
        $this->productCsvFileProcessor = $productCsvFileProcessor;
    }

    /**
     * @param $pathToProcessFile
     * @return array
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
                $this->report = $this->productCsvFileProcessor->getReport();
                break;
            case 'xlsx':
                echo ' dude, wrong house';
                break;
        }

        return $this->report;
    }
}
