<?php

declare(strict_types=1);

namespace App\Service\ImportTools;

class ProductImportFileValidator
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
     * @var int
     */
    private const PRODUCT_RULE_MIN_COST = 5;

    /**
     * @var int
     */
    private const PRODUCT_RULE_MAX_COST = 1000;

    /**
     * @var int
     */
    private const PRODUCT_RULE_STOCK_MIN_RULE = 10;

    /**
     * @param $row
     * @return bool
     */
    public function validate($row): bool
    {
        $isValid = true;
        if ($row[self::PRODUCT_STOCK_COLUMN] < self::PRODUCT_RULE_STOCK_MIN_RULE
            && (int) $row[self::PRODUCT_COST_COLUMN] < self::PRODUCT_RULE_MIN_COST
            or $row[self::PRODUCT_COST_COLUMN] > self::PRODUCT_RULE_MAX_COST) {
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
