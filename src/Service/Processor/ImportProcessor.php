<?php

declare(strict_types=1);

namespace App\Service\Processor;

use App\Exception\InvalidDataInFileException;
use App\Service\Factory\ReaderFactory;
use App\Service\Tool\MatrixToAssociativeArrayTransformer;
use App\Service\Tool\FileExtensionFinder;
use Exception;

class ImportProcessor
{
    /**
     * @var ProductCreator
     */
    private $productCreator;

    /**
     * @var ReaderFactory
     */
    private $readerFactory;

    /**
     * @var FileExtensionFinder
     */
    private $extensionFinder;

    /**
     * @var MatrixToAssociativeArrayTransformer
     */
    private $transformer;

    /**
     * ImportProcessor constructor.
     * @param ProductCreator $productCreator
     * @param ReaderFactory $readerFactory
     * @param FileExtensionFinder $extensionFinder
     * @param MatrixToAssociativeArrayTransformer $transformer
     */
    public function __construct(
        ProductCreator $productCreator,
        ReaderFactory $readerFactory,
        FileExtensionFinder $extensionFinder,
        MatrixToAssociativeArrayTransformer $transformer
    ) {
        $this->productCreator = $productCreator;
        $this->readerFactory = $readerFactory;
        $this->extensionFinder = $extensionFinder;
        $this->transformer = $transformer;
    }

    /**
     * @param $pathToProcessFile
     *
     * @return bool
     * @throws Exception
     */
    public function process($pathToProcessFile): bool
    {
        $isProcessSuccess = false;
        $rows = $this->readFile($pathToProcessFile);

        if($rows) {
            $rowsWithKeys = $this->transformArrayToAssociative($rows);
            $isProcessSuccess = $this->scheduleProductCreation($rowsWithKeys);
        } else {
            throw new InvalidDataInFileException('Invalid data in given file!');
        }

        return $isProcessSuccess;
    }

    /**
     * @param $pathToProcessFile
     * @return string|null
     * @throws Exception
     */
    public function getFileExtension($pathToProcessFile):? string
    {
        return $fileExtension = $this->extensionFinder->findFileExtensionFromPath($pathToProcessFile);
    }

    /**
     * @param $pathToProcessFile
     * @return object|null
     * @throws Exception
     */
    public function readFile($pathToProcessFile):? array
    {
        $fileExtension = $this->getFileExtension($pathToProcessFile);

        if($fileExtension) {
            $reader = $this->readerFactory->getFileReader($fileExtension);

            if ($reader) {
                $spreadSheet = $reader->load($pathToProcessFile);
                return $spreadSheet->getActiveSheet()->toArray();
            }
        }
        return null;
    }

    /**
     * @param $rows
     * @return array
     */
    public function transformArrayToAssociative($rows): array
    {
        return $this->transformer->transformArrayToAssociative($rows);
    }

    /**
     * @param $rowsWithKeys
     * @return bool
     * @throws Exception
     */
    public function scheduleProductCreation($rowsWithKeys): bool
    {
        $isProcessSuccess = false;

        if (count($rowsWithKeys) > 1) {
            $this->productCreator->createProducts($rowsWithKeys);
            $isProcessSuccess = true;
        }

        return $isProcessSuccess;
    }
}
