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
     * @var string
     */
    private $pathToProcessFile;

    /**
     * @var ImportProductsFromCsvFile
     */
    private $handlerCsv;

    /**
     * @var string
     */
    const INVALID_PRODUCTS = 'invalid_products';

    /**
     * @var string
     */
    const NUMBER_SAVED_PRODUCTS = 'number_saved_products';

    /**
     * @var string
     */
    const NUMBER_INVALID_PRODUCTS = 'number_invalid_products';

    /**
     * @var array
     */
    private $report = [];

    /**
     * ImportProductsFromFileCommand constructor.
     */
    public function __construct(ImportProductsFromCsvFile $handlerCsv)
    {
        $this->handlerCsv = $handlerCsv;
    }

    /**
     * @param $pathToProcessFile
     */
    public function setPathToFile($pathToProcessFile)
    {
        $fileNameParts = pathinfo($pathToProcessFile);

        $this->fileExtension = $fileNameParts['extension'];
    }

    /**
     * @throws \Exception
     */
    public function findRightHandler()
    {
        switch ($this->fileExtension):
            case 'xlsx':
            case 'csv':
                $reader = Reader::createFromPath($this->pathToProcessFile);
        $rows = $reader->fetchAssoc();
        $this->handlerCsv->validateAndCreate($rows);
        $this->report[self::INVALID_PRODUCTS] = $this->handlerCsv->getInvalidProducts();
        $this->report[self::NUMBER_SAVED_PRODUCTS] = $this->handlerCsv->getNumberSavedProducts();
        $this->report[self::NUMBER_INVALID_PRODUCTS] = $this->handlerCsv->getNumberInvalidProducts();

        break;
        endswitch;
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
        return $this->report[self::INVALID_PRODUCTS];
    }

    /**
     * @return int
     */
    public function getNumberSavedProducts()
    {
        return $this->report[self::NUMBER_SAVED_PRODUCTS];
    }

    /**
     * @return int
     */
    public function getNumberInvalidProducts()
    {
        return $this->report[self::NUMBER_INVALID_PRODUCTS];
    }
}
