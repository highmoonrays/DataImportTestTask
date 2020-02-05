<?php
namespace App\Service;


use App\Command\ImportProductsFromFileCommand;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductImportCSVFileReader
{
    /**
     * @var string
     */
    private const PRODUCT_NAME_COLUMN = 'Product Name';

    /**
     * @var string
     */
    private const PRODUCT_DESCRIPTION_COLUMN = 'Product Description';

    /**
     * @var string
     */
    private const PRODUCT_CODE_COLUMN = 'Product Code';

    /**
     * @var string
     */
    private const PRODUCT_COST_COLUMN = 'Cost in GBP';

    /**
     * @var string
     */
    private const PRODUCT_STOCK_COLUMN = 'Stock';

    /**
     * @var string
     */
    private const PRODUCT_DISCONTINUED_COLUMN = 'Discontinued';

    /**
     * @param $row
     * @return bool
     */
    public function validate($row)
    {
        $isValid = true;
        if ($row[self::PRODUCT_STOCK_COLUMN] < 10 && (int)$row[self::PRODUCT_COST_COLUMN] < 5
            or $row[self::PRODUCT_COST_COLUMN] > 1000) {
            $isValid = false;
        } else {
            if (!is_string($row[self::PRODUCT_NAME_COLUMN])
                or !is_string($row[self::PRODUCT_DESCRIPTION_COLUMN]) or !is_string($row[self::PRODUCT_CODE_COLUMN])
                or !is_numeric($row[self::PRODUCT_COST_COLUMN]) or !is_numeric($row[self::PRODUCT_STOCK_COLUMN])) {
                $isValid = false;
            }
        }
        return $isValid;
    }


    /**
     * @param $row
     * @param $isTestMode
     * @param EntityManagerInterface $em
     * @throws \Exception
     */
    public function save($row, $isTestMode, EntityManagerInterface $em)
    {
        $product = (new Product($row[self::PRODUCT_NAME_COLUMN],
            $row[self::PRODUCT_DESCRIPTION_COLUMN],
            $row[self::PRODUCT_CODE_COLUMN],
            new \DateTime(),
            new \DateTime(),
            $row[self::PRODUCT_STOCK_COLUMN],
            $row[self::PRODUCT_COST_COLUMN],
            ''));
        if ($row[self::PRODUCT_DISCONTINUED_COLUMN] == 'yes') {
            $product->setDiscontinued(new \DateTime());
            if ($isTestMode == false) {
                $em->persist($product);
                $em->flush();
            }
        }
    }
}