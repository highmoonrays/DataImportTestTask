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
     * CreateProductFromFile constructor.
     * @param $rowsWithKeys
     */
    public function __construct($rowsWithKeys)
    {
        $this->rowsWithKeys = $rowsWithKeys;
    }

    /**
     * @return array
     */
    public function getRowsWithKeys(): array
    {
        return $this->rowsWithKeys;
    }
}