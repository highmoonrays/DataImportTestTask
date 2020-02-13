<?php

declare(strict_types=1);

namespace App\Service\Tool;

class FileExtensionFinder
{
    /**
     * @var string
     */
    private const EXTENSION = 'extension';

    /**
     * @var string
     */
    private $fileExtension;

    /**
     * @param $pathToFile
     * @return string
     */
    public function findFileExtensionFromPath($pathToFile):? string
    {
        $fileNameParts = pathinfo($pathToFile);

        if(!$fileNameParts[self::EXTENSION]) {
            return null;
        }
        $this->fileExtension = $fileNameParts[self::EXTENSION];

        return $this->fileExtension;
    }
}