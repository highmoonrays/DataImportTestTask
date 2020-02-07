<?php

declare(strict_types=1);

namespace App\Service\CSV;

use App\Entity\Product;
use App\Service\ProductCreatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ProductFromCsvCreator implements ProductCreatorInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ProductCreatorInterface constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $row
     *
     * @throws Exception
     */
    public function save($row): void
    {
        $product = new Product($row[ProductImportCSVFileValidator::PRODUCT_NAME_COLUMN],
            $row[ProductImportCSVFileValidator::PRODUCT_DESCRIPTION_COLUMN],
            $row[ProductImportCSVFileValidator::PRODUCT_CODE_COLUMN],
            (int) $row[ProductImportCSVFileValidator::PRODUCT_STOCK_COLUMN],
            (int) $row[ProductImportCSVFileValidator::PRODUCT_COST_COLUMN],
            $row[ProductImportCSVFileValidator::PRODUCT_DISCONTINUED_COLUMN]);
        $this->em->persist($product);
    }
}
