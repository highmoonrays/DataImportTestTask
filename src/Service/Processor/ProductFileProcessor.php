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
    private $creator;

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
     * @param ProductImportFileCreator $creator
     * @param FileImportReporter $reporter
     * @param Converter $converter
     */
    public function __construct(
        ProductImportFileValidator $validator,
        ProductImportFileCreator $creator,
        FileImportReporter $reporter,
        Converter $converter
    ) {
        $this->validator = $validator;
        $this->creator = $creator;
        $this->reporter = $reporter;
        $this->converter = $converter;
    }

    /**
     * @param $rows
     *
     * @return bool
     * @throws Exception
     */
    public function importProductsFromFile($rows): bool
    {
        $isValid = false;
        $rowsWithKeys = $this->converter->arrayToAssociative($rows);
        foreach ($rowsWithKeys as $row) {
            $isValid = $this->validator->validate($row);

            if (true === $isValid) {
                $this->reporter->setNumberCreatedProducts($this->reporter->getNumberCreatedProducts() + 1);
                $this->creator->create($row);
            } else {
                $this->reporter->setInvalidProducts($row);
            }
        }
        return $isValid;
    }
}
