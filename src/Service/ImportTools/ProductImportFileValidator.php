<?php

declare(strict_types=1);

namespace App\Service\ImportTools;

use App\Service\Reporter\FileImportReporter;

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
     * @var FileImportReporter
     */
    private $reporter;

    /**
     * ProductImportFileValidator constructor.
     * @param FileImportReporter $reporter
     */
    public function __construct(FileImportReporter $reporter)
    {
        $this->reporter = $reporter;
    }

    /**
     * @param $row
     *
     * @return bool
     */
    public function validate($row): bool
    {
        $isValid = false;

        if ($row[self::PRODUCT_STOCK_COLUMN] < self::PRODUCT_RULE_STOCK_MIN_RULE
            && (int) $row[self::PRODUCT_COST_COLUMN] < self::PRODUCT_RULE_MIN_COST) {
            $this->reporter->setMessages('Stock is less than '.self::PRODUCT_RULE_STOCK_MIN_RULE.
                                        ' and cost less than '.self::PRODUCT_RULE_MIN_COST);
        }

        elseif ($row[self::PRODUCT_COST_COLUMN] > self::PRODUCT_RULE_MAX_COST) {
            $this->reporter->setMessages('Cost is more than '.self::PRODUCT_RULE_MAX_COST);
        }

        elseif (!is_string($row[self::PRODUCT_NAME_COLUMN])) {
            $this->reporter->setMessages('Invalid product name');
        }

        elseif (!is_string($row[self::PRODUCT_DESCRIPTION_COLUMN])) {
            $this->reporter->setMessages('Invalid product description');
        }

        elseif (!is_string($row[self::PRODUCT_CODE_COLUMN])) {
            $this->reporter->setMessages('Invalid product code');
        }

        elseif (!is_numeric($row[self::PRODUCT_COST_COLUMN])) {
            $this->reporter->setMessages('Invalid product cost');
        }

        elseif (!is_numeric($row[self::PRODUCT_STOCK_COLUMN])) {
            $this->reporter->setMessages('Invalid product stock');
        } else {
            $isValid = true;
        }

        return $isValid;
    }
}
