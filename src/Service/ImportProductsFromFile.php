<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\ImportTools\ProductImportFileCreator;
use App\Service\ImportTools\ProductImportFileValidator;
use App\Service\Reporter\FileImportReporter;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ImportProductsFromFile
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
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
     * ImportProductsFromFileCommand constructor.
     */

    /**
     * ImportProductsFromFile constructor.
     */
    public function __construct(
        EntityManagerInterface $em,
        ProductImportFileValidator $validator,
        ProductImportFileCreator $saver,
        FileImportReporter $reporter
    ) {
        $this->em = $em;
        $this->validator = $validator;
        $this->saver = $saver;
        $this->reporter = $reporter;
    }

    /**
     * @param $rows
     *
     * @throws Exception
     */
    public function import($rows): void
    {
        foreach ($rows as $row) {
            $isValid = $this->validator->validate($row);

            if (true === $isValid) {
                $this->reporter->setNumberSavedProducts($this->reporter->getNumberSavedProducts() + 1);
                $this->saver->save($row);
            } else {
                $this->reporter->setInvalidProducts($row);
            }
        }
    }
}
