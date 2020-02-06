<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="strProductCode", type="string", length=10, nullable=false)
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
    private $discontinued;

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
     * @param string $name
     * @param string $description
     * @param string $code
     * @param int $stock
     * @param int $cost
     * @param string $discontinued
     * @throws \Exception
     */
    public function __construct(string $name, string $description, string $code, int $stock,
                                int $cost, string $discontinued)
    {
        $this->name = $name;
        $this->description = $description;
        $this->code = $code;
        $this->added = new \DateTime();
        $this->timestamp = new \DateTime();
        $this->stock = $stock;
        $this->cost = $cost;
        if ('yes' === $discontinued) {
            $this->discontinued = new \DateTime();
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
     * @param string $name
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
     * @param string $description
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
     * @param string $code
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
     * @param \DateTimeInterface|null $added
     * @return $this
     */
    public function setAdded(?\DateTimeInterface $added): self
    {
        $this->added = $added;

        return $this;
    }

    public function getDiscontinued(): ?\DateTimeInterface
    {
        return $this->discontinued;
    }

    /**
     * @param \DateTimeInterface|null $discontinued
     * @return $this
     */
    public function setDiscontinued(?\DateTimeInterface $discontinued): self
    {
        $this->discontinued = $discontinued;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTimeInterface $timestamp
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
     * @param int|null $Stock
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
     * @param int $Cost
     *
     * @return $this
     */
    public function setCost(int $Cost): self
    {
        $this->cost = $Cost;

        return $this;
    }
}
