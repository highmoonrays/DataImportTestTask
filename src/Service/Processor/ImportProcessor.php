<?php

declare(strict_types=1);

namespace App\Service\Processor;

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
        $fileExtension = $this->extensionFinder->findFileExtensionFromPath($pathToProcessFile);

        if($fileExtension) {
            $reader = $this->readerFactory->getFileReader($fileExtension);

            if($reader){
                $spreadSheet = $reader->load($pathToProcessFile);
                $rows = $spreadSheet->getActiveSheet()->toArray();
                $rowsWithKeys = $this->transformer->transformArrayToAssociative($rows);
                $this->productCreator->createProducts($rowsWithKeys);
                $isProcessSuccess = true;
            }
        }

        return $isProcessSuccess;
    }
}
