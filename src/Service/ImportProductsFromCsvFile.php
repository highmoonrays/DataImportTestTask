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
    private $counterInvalidItems = 0;

    /**
     * @var int
     */
    private $counterSavedItems = 0;

    /**
     * @var array
     */
    public $report;

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
     * @return array
     *
     * @throws Exception
     */
    public function validateAndCreate($rows)
    {
        foreach ($rows as $row) {
            $isValid = $this->validator->validate($row);
            if (true === $isValid) {
                ++$this->counterSavedItems;
                $this->saver->save($row);
            } else {
                ++$this->counterInvalidItems;
                $this->invalidProducts[] = $row;
            }
        }
        array_push($this->report, $this->invalidProducts);
        array_push($this->report, $this->counterInvalidItems);
        array_push($this->report, $this->counterSavedItems);

        return $this->report;
    }
}
