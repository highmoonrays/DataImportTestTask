<?php

declare(strict_types=1);

namespace App\Service\Reporter;

class FileImportReporter
{
    /**
     * @var array
     */
    private $invalidProducts;

    /**
     * @var int
     */
    private $numberSavedProducts = 0;

    /**
     * @return int
     */
    public function getNumberSavedProducts(): int
    {
        return $this->numberSavedProducts;
    }

    /**
     * @param int $numberSavedProducts
     */
    public function setNumberSavedProducts(int $numberSavedProducts): void
    {
        $this->numberSavedProducts = $numberSavedProducts;
    }

    /**
     * @return array
     */
    public function getInvalidProducts(): array
    {
        return $this->invalidProducts;
    }

    /**
     * @param array $invalidProducts
     */
    public function setInvalidProducts(array $invalidProducts): void
    {
        $this->invalidProducts[] = $invalidProducts;
    }

}
