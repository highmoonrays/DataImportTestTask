<?php

declare(strict_types=1);

namespace App\Service\Reporter;

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
    private $numberSavedProducts = 0;

    public function getNumberSavedProducts(): int
    {
        return $this->numberSavedProducts;
    }

    public function setNumberSavedProducts(int $numberSavedProducts): void
    {
        $this->numberSavedProducts = $numberSavedProducts;
    }

    public function getInvalidProducts(): array
    {
        return $this->invalidProducts;
    }

    public function setInvalidProducts(array $invalidProducts): void
    {
        $this->invalidProducts[] = $invalidProducts;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param array $message
     */
    public function setMessages(string $messages): void
    {
        $this->messages[] = $messages;
    }
}
