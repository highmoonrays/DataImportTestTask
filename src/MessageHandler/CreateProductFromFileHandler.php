<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\CreateProductFromFile;
use App\Service\Processor\ImportProcessor;
use App\Service\Reporter\FileImportReporter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateProductFromFileHandler implements MessageHandlerInterface
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
     * CreateProductFromUploadedFileController constructor.
     * @param ImportProcessor $importProcessor
     * @param FileImportReporter $importReporter
     * @param EntityManagerInterface $em
     */
    public function __construct(
        ImportProcessor $importProcessor,
        FileImportReporter $importReporter,
        EntityManagerInterface $em)
    {
        $this->importReporter = $importReporter;
        $this->importProcessor = $importProcessor;
        $this->em = $em;
    }

    /**
     * @param CreateProductFromFile $createProductFromFile
     * @return void
     * @throws \Exception
     */
    public function __invoke(CreateProductFromFile $createProductFromFile): void
    {
        $rowsWithKeys = $createProductFromFile->getRowsWithKeys();
        $this->importProcessor->scheduleProductCreation($rowsWithKeys);
        $this->em->flush();
    }
}