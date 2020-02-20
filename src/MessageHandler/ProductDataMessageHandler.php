<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ProductDataMessage;
use App\Service\Processor\ImportProcessor;
use App\Service\Processor\ProductCreator;
use App\Service\Reporter\FileImportReporter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ProductDataMessageHandler implements MessageHandlerInterface
{

    /**
     * @var ImportProcessor
     */
    private $importProcessor;

    /**
     * @var FileImportReporter
     */
    private $importReporter;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ProductCreator
     */
    private $productCreator;

    /**
     * CreateProductFromUploadedFileController constructor.
     * @param ImportProcessor $importProcessor
     * @param FileImportReporter $importReporter
     * @param EntityManagerInterface $em
     * @param ProductCreator $productCreator
     */
    public function __construct(
        ImportProcessor $importProcessor,
        FileImportReporter $importReporter,
        EntityManagerInterface $em,
        ProductCreator $productCreator)
    {
        $this->importReporter = $importReporter;
        $this->importProcessor = $importProcessor;
        $this->em = $em;
        $this->productCreator = $productCreator;
    }

    /**
     * @param ProductDataMessage $productDataMessage
     * @throws \Exception
     */
    public function __invoke(ProductDataMessage $productDataMessage)
    {
        $rowWithKeys = $productDataMessage->getRowWithKeys();

        $this->productCreator->createProducts($rowWithKeys);

        if(false === $isTestMode = $productDataMessage->isTest()){
            $this->em->flush();
        }
    }
}