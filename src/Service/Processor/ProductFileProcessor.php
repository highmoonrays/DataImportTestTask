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
    private $saver;

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
     * @param ProductImportFileCreator $saver
     * @param FileImportReporter $reporter
     */
    public function __construct(
        ProductImportFileValidator $validator,
        ProductImportFileCreator $saver,
        FileImportReporter $reporter
    ) {
        $this->validator = $validator;
        $this->saver = $saver;
        $this->reporter = $reporter;
    }

    /**
     * @param $rows
     *
     * @throws Exception
     */
    public function importProductsFromFile($rows): void
    {
        $headers = $rows[0];
        unset($rows[0]);
        $rowsWithKeys = [];

        foreach ($rows as $row) {
            $newRow = [];

            foreach ($headers as $key => $value) {
                $newRow[$value] = $row[$key];
            }
            $rowsWithKeys[] = $newRow;
        }

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
