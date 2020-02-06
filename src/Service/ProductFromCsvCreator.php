<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

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
     * @throws \Exception
     */
    public function save($row): void
    {
        $product = new Product($row[ProductImportCSVFileReader::PRODUCT_NAME_COLUMN],
            $row[ProductImportCSVFileReader::PRODUCT_DESCRIPTION_COLUMN],
            $row[ProductImportCSVFileReader::PRODUCT_CODE_COLUMN],
            (int) $row[ProductImportCSVFileReader::PRODUCT_STOCK_COLUMN],
            (int) $row[ProductImportCSVFileReader::PRODUCT_COST_COLUMN],
            $row[ProductImportCSVFileReader::PRODUCT_DISCONTINUED_COLUMN]);
        $this->em->persist($product);
    }
}
