<?php

declare(strict_types=1);

namespace App\Service\Processor;

use App\Service\ImportTool\ProductFromFileCreator;
use App\Service\ImportTool\FileDataValidator;
use App\Service\Reporter\FileImportReporter;
use Exception;

class ProductCreatorProcessor
{
    /**
     * @var FileDataValidator
     */
    private $validator;
    /**
     * @var ProductFromFileCreator
     */
    private $creator;

    /**
     * @var FileImportReporter
     */
    private $reporter;

    /**
     * ProductCreatorProcessor constructor.
     * @param FileDataValidator $validator
     * @param ProductFromFileCreator $creator
     * @param FileImportReporter $reporter
     */
    public function __construct(
        FileDataValidator $validator,
        ProductFromFileCreator $creator,
        FileImportReporter $reporter
    ) {
        $this->validator = $validator;
        $this->creator = $creator;
        $this->reporter = $reporter;
    }

    /**
     * @param $rowsWithKeys
     * @return void
     * @throws Exception
     */
    public function createProducts($rowsWithKeys): void
    {

        foreach ($rowsWithKeys as $row) {
            $isValid = $this->validator->validate($row);

            if (true === $isValid) {
                $this->reporter->setNumberCreatedProducts($this->reporter->getNumberCreatedProducts() + 1);
                $this->creator->create($row);
            } else {
                $this->reporter->setInvalidProducts($row);
            }
        }
    }
}
