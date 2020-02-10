<?php

declare(strict_types=1);

namespace App\tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\DateTime;

class ProductTest extends TestCase
{

    /**
     * @throws \Exception
     */
    public function testProduct()
    {
        $product = new Product(
            'TestName',
            'TestDescription',
            'TestCode0000',
            5,
            0,
            false
        );

        $this->assertSame('TestName', $product->getName());
        $this->assertSame('TestDescription', $product->getDescription());
        $this->assertSame('TestCode0000', $product->getCode());
        $this->assertSame(5, $product->getStock());
        $this->assertSame(0, $product->getCost());

        if (true === $product->getIsDiscontinued()) {
            $this->assertIsObject($product->getIsDiscontinued(), 'An object');
        }

        else {
            $this->assertEmpty($product->getIsDiscontinued(), 'Not an object');
        }

    }
}