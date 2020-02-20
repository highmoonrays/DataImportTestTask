<?php

declare(strict_types=1);

namespace App\Message;

class ProductDataMessage
{
    /**
     * @var array
     */
    private $rowWithKeys;

    /**
     * @var bool
     */
    private $isTestMode;

    /**
     * ProductDataMessage constructor.
     * @param $rowWithKeys
     * @param $isTestMode
     */
    public function __construct($rowWithKeys, $isTestMode)
    {
        $this->rowWithKeys = $rowWithKeys;
        $this->isTestMode = $isTestMode;
    }

    /**
     * @return array
     */
    public function getRowWithKeys(): array
    {
        return $this->rowWithKeys;
    }

    /**
     * @return bool
     */
    public function isTest(): bool
    {
        return $this->isTestMode;
    }
}