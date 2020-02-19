<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\UploadFileToCreateProductType;
use App\Message\CreateProductFromFile;
use App\Service\Processor\ImportProcessor;
use App\Service\Reporter\FileImportReporter;
use App\Service\Uploader\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;

class CreateProductFromUploadedFileController extends AbstractController
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

    public function __invoke(Publisher $publisher): Response
    {
        $update = new Update(
            'https://localhost:8000/createProductFromUploadedFile',
            "[]"
        );

        $publisher($update);

        return new Response('published!');
    }

    /**
     * @Route("/createProductFromUploadedFile", name="createProductFromUploadedFile")
     * @param string $uploadDir
     * @param FileUploader $uploader
     * @param Request $request
     * @param MessageBusInterface $messageBus
     * @return Response
     * @throws \Exception
     */
    public function createProductFromUploadedFile(
        string $uploadDir,
        FileUploader $uploader,
        Request $request,
        MessageBusInterface $messageBus): Response
    {
        $form = $this->createForm(UploadFileToCreateProductType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $pathToFile = $uploader->upload($uploadDir, $file);
            $rows = $this->importProcessor->readFile($pathToFile);
            $rowsWithKeys = $this->importProcessor->transformArrayToAssociative($rows);
            $isTestMode = false;

            if (true === $form->get('isTest')->getData()) {
                $isTestMode = true;
            }

            $message = new CreateProductFromFile($rowsWithKeys, $isTestMode);
            $messageBus->dispatch($message);

            return $this->render('load/report.html.twig', [
                'processMessage' => 'Process has been started!'
            ]);
        }

        return $this->render('load/loadFile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}