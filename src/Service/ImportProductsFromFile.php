<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\ImportTools\ProductImportFileCreator;
use App\Service\ImportTools\ProductImportFileValidator;
use App\Service\Reporter\AfterReadReporter;
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
     * @var array
     */
    private $invalidProducts;

    /**
     * @var int
     */
    private $numberSavedProducts = 0;

    /**
     * @var AfterReadReporter
     */
    private $reporter;

    /**
     * ImportProductsFromFileCommand constructor.
     */

    /**
     * ImportProductsFromFile constructor.
     * @param EntityManagerInterface $em
     * @param ProductImportFileValidator $validator
     * @param ProductImportFileCreator $saver
     * @param AfterReadReporter $reporter
     */
    public function __construct(
        EntityManagerInterface $em,
        ProductImportFileValidator $validator,
        ProductImportFileCreator $saver,
        AfterReadReporter $reporter
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
    public function Import($rows)
    {
        foreach ($rows as $row) {
            $isValid = $this->validator->validate($row);
            if (true === $isValid) {
                ++$this->numberSavedProducts;
                $this->saver->save($row);
            } else {
                $this->invalidProducts[] = $row;
            }
        }
        $this->reporter->setReport($this->invalidProducts, $this->numberSavedProducts);
    }
}
