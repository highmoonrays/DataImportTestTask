<?php

declare(strict_types=1);

namespace App\Service\Processor;

use App\Service\ImportTool\Converter;
use App\Service\ImportTool\ProductImportFileCreator;
use App\Service\ImportTool\ProductImportFileValidator;
use App\Service\Reporter\FileImportReporter;
use Exception;

class ProductFileProcessor
{
    /**
     * @var ProductImportFileValidator
     */
    private $validator;
    /**
     * @var ProductImportFileCreator
     */
    private $saver;

    /**
     * @var FileImportReporter
     */
    private $reporter;

    /**
     * @var Converter
     */
    private $converter;

    /**
     * ImportProductsFromFile constructor.
     */

    /**
     * ProductFileProcessor constructor.
     * @param ProductImportFileValidator $validator
     * @param ProductImportFileCreator $saver
     * @param FileImportReporter $reporter
     * @param Converter $converter
     */
    public function __construct(
        ProductImportFileValidator $validator,
        ProductImportFileCreator $saver,
        FileImportReporter $reporter,
        Converter $converter
    ) {
        $this->validator = $validator;
        $this->saver = $saver;
        $this->reporter = $reporter;
        $this->converter = $converter;
    }

    /**
     * @param $rows
     *
     * @throws Exception
     */
    public function importProductsFromFile($rows): void
    {
        $rowsWithKeys = $this->converter->arrayToAssociative($rows);
        foreach ($rowsWithKeys as $row) {
            $isValid = $this->validator->validate($row);

            if (true === $isValid) {
                $this->reporter->setNumberSavedProducts($this->reporter->getNumberSavedProducts() + 1);
                $this->saver->create($row);
            } else {
                $this->reporter->setInvalidProducts($row);
            }
        }
    }
}
