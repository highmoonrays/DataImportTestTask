<?php

declare(strict_types=1);

namespace App\Form\DataTransferObject;

use Symfony\Component\Validator\Constraints as Assert;
use App\Service\ImportTool\FileDataValidator;

class ProductDTO
{
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Regex(
     *    FileDataValidator::REGULAR_EXPRESSION_TO_AVOID_SPECIAL_CHARACTERS,
     *    message="Please, enter valid name"
     * )
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Regex(
     *     FileDataValidator::REGULAR_EXPRESSION_TO_AVOID_SPECIAL_CHARACTERS,
     *     message="Please, enter valid description"
     * )
     */
    private $description;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Regex(
     *     FileDataValidator::REGULAR_EXPRESSION_TO_AVOID_SPECIAL_CHARACTERS,
     *     message="Please, enter valid code"
     * )
     */
    private $code;

    /**
     * @var bool
     */
    private $isDiscontinued;

    /**
     * @var int
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(10)
     */
    private $stock;

    /**
     * @var int
     * @Assert\NotBlank
     * @Assert\Range(
     *     min = 5,
     *     max = 1000
     * )
     */
    private $cost;

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

    /**
     * @return bool
     */
    public function isDiscontinued():? bool
    {
        return $this->isDiscontinued;
    }

    /**
     * @param bool $isDiscontinued
     */
    public function setIsDiscontinued(bool $isDiscontinued): void
    {
        $this->isDiscontinued = $isDiscontinued;
    }
}