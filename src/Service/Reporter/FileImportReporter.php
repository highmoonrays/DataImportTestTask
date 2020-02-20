<?php

declare(strict_types=1);

namespace App\Service\Reporter;

use App\Service\ImportTool\FileDataValidator;

class FileImportReporter
{
    /**
     * @var array
     */
    private $invalidProducts;

    /**
     * @var array
     */
    private $messages;

    /**
     * @var int
     */
    private $numberCreatedProducts = 0;

    /**
     * @return int
     */
    public function getNumberCreatedProducts():? int
    {
        return $this->numberCreatedProducts;
    }

    /**
     * @param int $numberCreatedProducts
     */
    public function setNumberCreatedProducts(int $numberCreatedProducts): void
    {
        $this->numberCreatedProducts = $numberCreatedProducts;
    }

    /**
     * @return array
     */
    public function getInvalidProducts():? array
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

    /**
     * @return array
     */
    public function getMessages():? array
    {
        return $this->messages;
    }

    /**
     * @param string $message
     */
    public function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }

    /**
     * @return void
     */
    public function clearReport(): void
    {
        $this->messages = null;
        $this->invalidProducts = null;
    }

    /**
     * @return string
     */
    public function getReportForOneInvalidProduct():? string
    {
        $message = $this->messages;
        $invalidProduct = $this->invalidProducts;

        return $invalidProduct[0][FileDataValidator::PRODUCT_CODE_COLUMN].' '.
            $invalidProduct[0][FileDataValidator::PRODUCT_NAME_COLUMN].' '.
            $invalidProduct[0][FileDataValidator::PRODUCT_DESCRIPTION_COLUMN].' '.
            $invalidProduct[0][FileDataValidator::PRODUCT_STOCK_COLUMN].' '.
            $invalidProduct[0][FileDataValidator::PRODUCT_COST_COLUMN].' '.
            $invalidProduct[0][FileDataValidator::PRODUCT_DISCONTINUED_COLUMN] .' || Fatal Error: '
            .$message[0];
    }
}
