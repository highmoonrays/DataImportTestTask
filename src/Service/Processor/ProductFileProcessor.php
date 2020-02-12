<?php

declare(strict_types=1);

namespace App\Service\Processor;

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
     * ImportProductsFromFile constructor.
     */

    /**
     * ProductFileProcessor constructor.
     * @param ProductImportFileValidator $validator
     * @param ProductImportFileCreator $creator
     * @param FileImportReporter $reporter
     */
    public function __construct(
        ProductImportFileValidator $validator,
        ProductImportFileCreator $creator,
        FileImportReporter $reporter
    ) {
        $this->validator = $validator;
        $this->creator = $creator;
        $this->reporter = $reporter;
    }

    /**
     * @param $rowsWithKeys
     * @return bool
     * @throws Exception
     */
    public function importProductsFromFile($rowsWithKeys): bool
    {
        $isValid = false;

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
