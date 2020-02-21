<?php

declare(strict_types=1);

namespace App\Form\DataTransferObject;

use App\Service\ImportTool\FileDataValidator;
use App\Service\Processor\ProductCreator;

class ProductDTO
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $discontinued;

    /**
     * @var int
     */
    private $stock;

    /**
     * @var int
     */
    private $cost;

    /**
     * @var ProductCreator
     */
    private $creator;

    /**
     * ProductDTO constructor.
     * @param ProductCreator $creator
     */
    public function __construct(ProductCreator $creator)
    {
        $this->creator = $creator;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function createProduct(): bool
    {
        $productData = [
            [
            FileDataValidator::PRODUCT_NAME_COLUMN => $this->name,
            FileDataValidator::PRODUCT_DESCRIPTION_COLUMN => $this->description,
            FileDataValidator::PRODUCT_CODE_COLUMN => $this->code,
            FileDataValidator::PRODUCT_STOCK_COLUMN => $this->stock,
            FileDataValidator::PRODUCT_COST_COLUMN => $this->cost,
            FileDataValidator::PRODUCT_DISCONTINUED_COLUMN => $this->discontinued
            ]
        ];
        return $this->creator->createProducts($productData);
    }

    /**
     * @return string
     */
    public function getName():? string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription():? string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getCode():? string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string|null
     */
    public function getDiscontinued():? string
    {
        return $this->discontinued;
    }

    /**
     * @param string $discontinued
     */
    public function setDiscontinued(string $discontinued): void
    {
        $this->discontinued = $discontinued;
    }

    /**
     * @return int
     */
    public function getStock():? int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * @return int
     */
    public function getCost():? int
    {
        return $this->cost;
    }

    /**
     * @param int $cost
     */
    public function setCost(int $cost): void
    {
        $this->cost = $cost;
    }
}