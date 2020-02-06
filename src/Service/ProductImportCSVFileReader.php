<?php
declare(strict_types=1);
namespace App\Service;
class ProductImportCSVFileReader
{
    /**
     * @var string
     */
    public const PRODUCT_NAME_COLUMN = 'Product Name';

    /**
     * @var string
     */
    public const PRODUCT_DESCRIPTION_COLUMN = 'Product Description';

    /**
     * @var string
     */
    public const PRODUCT_CODE_COLUMN = 'Product Code';

    /**
     * @var string
     */
    public const PRODUCT_COST_COLUMN = 'Cost in GBP';

    /**
     * @var string
     */
    public const PRODUCT_STOCK_COLUMN = 'Stock';

    /**
     * @var string
     */
    public const PRODUCT_DISCONTINUED_COLUMN = 'Discontinued';

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
                or !is_string($row[self::PRODUCT_DESCRIPTION_COLUMN])
                or !is_string($row[self::PRODUCT_CODE_COLUMN])
                or !is_numeric($row[self::PRODUCT_COST_COLUMN])
                or !is_numeric($row[self::PRODUCT_STOCK_COLUMN])) {
                $isValid = false;
            }
        }

        return $isValid;
    }
}