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
     * ImportProductsFromFileCommand constructor.
     * @param ImportProductsFromCsvFile $productCsvFileProcessor
     */
    public function __construct(ImportProductsFromCsvFile $productCsvFileProcessor)
    {
        $this->productCsvFileProcessor = $productCsvFileProcessor;
    }

    /**
     * @param $pathToProcessFile
     *
     * @return null
     *
     * @throws \Exception
     */
    public function process($pathToProcessFile)
    {
        $fileNameParts = pathinfo($pathToProcessFile);
        $this->fileExtension = $fileNameParts['extension'];

        switch ($this->fileExtension) {
            case 'csv':
                $reader = Reader::createFromPath($pathToProcessFile);
                $rows = $reader->fetchAssoc();
                $this->productCsvFileProcessor->validateAndCreate($rows);
                break;
            case 'xlsx':
                break;
            default:
                return null;
        }
    }
}
