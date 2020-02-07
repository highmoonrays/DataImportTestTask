<?php

namespace App\Service\Reporter;

class AfterReadReporter
{
    /**
     * @var string
     */
    public const REPORT_INVALID_PRODUCTS = 'invalid_products';

    /**
     * @var string
     */
    public const REPORT_NUMBER_SAVED_PRODUCTS = 'number_saved_products';

    /**
     * @var array
     */
    private $report = [];

    /**
     * @param array $invalidProducts
     * @param int $numberSavedItems
     */
    public function setReport(array $invalidProducts, int $numberSavedItems): void
    {
        $this->report[self::REPORT_INVALID_PRODUCTS] = $invalidProducts;
        $this->report[self::REPORT_NUMBER_SAVED_PRODUCTS] = $numberSavedItems;
    }

    /**
     * @return array
     */
    public function getReport()
    {
        return $this->report;
    }
}
