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
     * @var array
     */
    private $report = [];

    /**
     * ImportProductsFromFileCommand constructor.
     */

    /**
     * ImportProductsFromCsvFile constructor.
     */
    public function __construct(EntityManagerInterface $em,
                                ProductImportCSVFileReader $validator,
                                ProductFromCsvCreator $saver)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->saver = $saver;
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
    }

    public function getReport()
    {
        $this->report[ImportHelperFactory::REPORT_INVALID_PRODUCTS] = $this->invalidProducts;
        $this->report[ImportHelperFactory::REPORT_NUMBER_SAVED_PRODUCTS] = $this->numberSavedProducts;

        return $this->report;
    }
}
