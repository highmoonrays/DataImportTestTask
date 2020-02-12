<?php

declare(strict_types=1);

namespace App\Service\ImportTool;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ProductFromFileCreator implements ProductCreatorInterface
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
    public function create($row): void
    {
        $isDiscontinued = false;

        if ('yes' === $row[FileDataValidator::PRODUCT_DISCONTINUED_COLUMN]) {
            $isDiscontinued = true;
        }
        $product = new Product(
            $row[FileDataValidator::PRODUCT_NAME_COLUMN],
            $row[FileDataValidator::PRODUCT_DESCRIPTION_COLUMN],
            $row[FileDataValidator::PRODUCT_CODE_COLUMN],
            (int) $row[FileDataValidator::PRODUCT_STOCK_COLUMN],
            (int) $row[FileDataValidator::PRODUCT_COST_COLUMN],
            $isDiscontinued
        );
        $this->em->persist($product);
    }
}
