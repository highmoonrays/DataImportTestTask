<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ImportProductsFromCsvFile
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ProductImportCSVFileReader
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
     * @param ProductImportCSVFileReader $validator
     * @param ProductFromCsvCreator $saver
     * @param AfterReadReporter $reporter
     */
    public function __construct(EntityManagerInterface $em,
                                ProductImportCSVFileReader $validator,
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
