<?php

declare(strict_types=1);

namespace App\Service\CSV;

use App\Service\Reporter\AfterReadReporter;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ImportProductsFromCsvFile
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ProductImportCSVFileValidator
     */
    private $validator;
    /**
     * @var ProductFromCsvCreator
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
     * ImportProductsFromCsvFile constructor.
     * @param EntityManagerInterface $em
     * @param ProductImportCSVFileValidator $validator
     * @param ProductFromCsvCreator $saver
     * @param AfterReadReporter $reporter
     */
    public function __construct(EntityManagerInterface $em,
                                ProductImportCSVFileValidator $validator,
                                ProductFromCsvCreator $saver,
                                AfterReadReporter $reporter)
    {
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
    public function validateAndCreate($rows)
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
