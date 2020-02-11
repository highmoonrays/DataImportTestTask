<?php

declare(strict_types=1);

namespace App\tests\Entity;

use App\Entity\Product;
use Exception;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /**
     * @var Product
     */
    private $product;
    /**
     * @throws Exception
     */
    public function setUp()
    {
        $this->product = new Product(
            'TestName',
            'TestDescription',
            'TestCode0000',
            5,
            0,
            false
            )
        ;
    }

    public function testCreateProduct()
    {
        $this->assertSame('TestName', $this->product->getName());
        $this->assertSame('TestDescription', $this->product->getDescription());
        $this->assertSame('TestCode0000', $this->product->getCode());
        $this->assertSame(5, $this->product->getStock());
        $this->assertSame(0, $this->product->getCost());

        if (true === $this->product->getIsDiscontinued()) {
            $this->assertIsObject($this->product->getIsDiscontinued());
        } else {
            $this->assertEmpty($this->product->getIsDiscontinued());
        }
    }
}