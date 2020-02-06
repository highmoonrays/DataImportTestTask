<?php
namespace App\Service;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
class ProductFromCsvCreator extends ProductCreator
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ProductCreator constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }
    /**
     * @param $row
     * @throws \Exception
     */
    public function save($row)
    {
        $product = (new Product($row[ProductImportCSVFileReader::PRODUCT_NAME_COLUMN],
            $row[ProductImportCSVFileReader::PRODUCT_DESCRIPTION_COLUMN],
            $row[ProductImportCSVFileReader::PRODUCT_CODE_COLUMN],
            $row[ProductImportCSVFileReader::PRODUCT_STOCK_COLUMN],
            $row[ProductImportCSVFileReader::PRODUCT_COST_COLUMN],
            $row[ProductImportCSVFileReader::PRODUCT_DISCONTINUED_COLUMN]));
        $this->em->persist($product);
    }

}