<?php

declare(strict_types=1);

namespace App\Message;

class CreateProductFromFile
{
    /**
     * @var string
     */
    private $pathToFile;

    /**
     * CreateProductFromFile constructor.
     * @param $pathToFile
     */
    public function __construct($pathToFile)
    {
        $this->pathToFile = $pathToFile;
    }

    /**
     * @return string
     */
    public function getPathToFile(): string
    {
        return $this->pathToFile;
    }
}