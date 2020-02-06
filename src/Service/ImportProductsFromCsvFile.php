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
    private $numberInvalidProducts = 0;

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
                ++$this->numberInvalidProducts;
                $this->invalidProducts[] = $row;
            }
        }
    }

    /**
     * @return array
     */
    public function getInvalidProducts()
    {
        return $this->invalidProducts;
    }

    /**
     * @return int
     */
    public function getNumberSavedProducts()
    {
        return $this->numberSavedProducts;
    }

    /**
     * @return int
     */
    public function getNumberInvalidProducts()
    {
        return $this->numberInvalidProducts;
    }
}
