<?php

declare(strict_types=1);

namespace App\Service\ImportTools;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ProductImportFileCreator implements ProductCreatorInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ProductCreatorInterface constructor.
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
        $isDiscontinued = false;

        if ('yes' === $row[ProductImportFileValidator::PRODUCT_DISCONTINUED_COLUMN]) {
            $isDiscontinued = true;
        }
        $product = new Product($row[ProductImportFileValidator::PRODUCT_NAME_COLUMN],
            $row[ProductImportFileValidator::PRODUCT_DESCRIPTION_COLUMN],
            $row[ProductImportFileValidator::PRODUCT_CODE_COLUMN],
            (int) $row[ProductImportFileValidator::PRODUCT_STOCK_COLUMN],
            (int) $row[ProductImportFileValidator::PRODUCT_COST_COLUMN],
            $isDiscontinued
            );
        $this->em->persist($product);
    }
}
