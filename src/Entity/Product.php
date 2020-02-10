<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * Product.
 *
 * @ORM\Table(name="tblProductData", uniqueConstraints={@ORM\UniqueConstraint(name="tblProductData", columns={"intProductDataId"})})
 * @ORM\Entity
 */
class Product
{
    /**
     * @var int
     * @ORM\Column(name="intProductDataId", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="strProductName", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="strProductDesc", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(name="strProductCode", type="string", length=10, nullable=false, unique=true)
     */
    private $code;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="dtmAdded", type="datetime", nullable=true)
     */
    private $added;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="dtmDiscontinued", type="datetime", nullable=true)
     */
    private $isDiscontinued;

    /**
     * @var \DateTime
     * @ORM\Column(name="stmTimestamp", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $timestamp;

    /**
     * @ORM\Column(name="intStock", type="integer", nullable=true)
     *
     * @var int
     */
    private $stock;

    /**
     * @ORM\Column(name="intCostInGBP", type="integer")
     *
     * @var int
     */
    private $cost;

    /**
     * Product constructor.
     *
     * @throws Exception
     */
    public function __construct(
        string $name,
        string $description,
        string $code,
        int $stock,
        int $cost,
        bool $isDiscontinued
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->code = $code;
        $this->added = new \DateTime();
        $this->timestamp = new \DateTime();
        $this->stock = $stock;
        $this->cost = $cost;
        if (true === $isDiscontinued) {
            $this->isDiscontinued = new \DateTime();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return $this
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAdded(): ?\DateTimeInterface
    {
        return $this->added;
    }

    /**
     * @return $this
     */
    public function setAdded(?\DateTimeInterface $added): self
    {
        $this->added = $added;

        return $this;
    }

    public function getIsDiscontinued(): ?\DateTimeInterface
    {
        return $this->isDiscontinued;
    }

    /**
     * @return $this
     */
    public function setIsDiscontinued(?\DateTimeInterface $isDiscontinued): self
    {
        $this->isDiscontinued = $isDiscontinued;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    /**
     * @return $this
     */
    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    /**
     * @return $this
     */
    public function setStock(?int $Stock): self
    {
        $this->stock = $Stock;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    /**
     * @return $this
     */
    public function setCost(int $Cost): self
    {
        $this->cost = $Cost;

        return $this;
    }
}
