<?php

declare(strict_types=1);

namespace App\Service\Processor;

use App\Service\Factory\ReaderFactory;
use App\Service\Tool\Converter;
use App\Service\Tool\FileExtensionFinder;
use Exception;

class ImportProcessor
{
    /**
     * @var ProductCreatorProcessor
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
     * @var Converter
     */
    private $converter;

    /**
     * ImportProductsFromFile constructor.
     * @param ProductCreatorProcessor $productCreator
     * @param ReaderFactory $readerFactory
     * @param FileExtensionFinder $extensionFinder
     * @param Converter $converter
     */
    public function __construct(
        ProductCreatorProcessor $productCreator,
        ReaderFactory $readerFactory,
        FileExtensionFinder $extensionFinder,
        Converter $converter
    ) {
        $this->productCreator = $productCreator;
        $this->readerFactory = $readerFactory;
        $this->extensionFinder = $extensionFinder;
        $this->converter = $converter;
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
        $fileExtension = $this->extensionFinder->findFileExtensionFromPath($pathToProcessFile);
        $reader = $this->readerFactory->getFileReader($fileExtension);

        if (null === $reader) {
            return $isProcessSuccess;
        } else {
            $spreadSheet = $reader->load($pathToProcessFile);
            $rows = $spreadSheet->getActiveSheet()->toArray();
            $rowsWithKeys = $this->converter->convertArrayToAssociative($rows);
            $this->productCreator->createProducts($rowsWithKeys);
            $isProcessSuccess = true;
        }

        return $isProcessSuccess;
    }
}
