<?php

declare(strict_types=1);

namespace App\Message;

class CreateProductFromFile
{
    /**
     * @var array
     */
    private $rowsWithKeys;

    /**
     * @var bool
     */
    private $isTestMode;

    /**
     * CreateProductFromFile constructor.
     * @param $rowsWithKeys
     * @param $isTestMode
     */
    public function __construct($rowsWithKeys, $isTestMode)
    {
        $this->rowsWithKeys = $rowsWithKeys;
        $this->isTestMode = $isTestMode;
    }

    /**
     * @return array
     */
    public function getRowsWithKeys(): array
    {
        return $this->rowsWithKeys;
    }

    public function isTest()
    {
        return $this->isTestMode;
    }
}