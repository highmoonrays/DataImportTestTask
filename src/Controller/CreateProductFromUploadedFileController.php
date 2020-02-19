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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

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

    /**
     * @param Publisher $publisher
     * @return Response
     */
    public function __invoke(Publisher $publisher): Response
    {
        $update = new Update(
            'localhost:8000/createProductFromUploadedFile',
            json_encode(['products are valid' => 'All products are valid, and successfully created'])
        );

        $publisher($update);

        return new Response('created!');
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
            $this->addFlash('Process is started', 'Process has been started');

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
//            $invalidProducts = $this->importReporter->getInvalidProducts();
//
//            if ($invalidProducts) {
//                $messages = $this->importReporter->getMessages();
//
//                return $this->render('load/report.html.twig', [
//                    'numberInvalidProducts' => count($this->importReporter->getInvalidProducts()),
//                    'messages' => $messages,
//                    'invalidProducts' => $invalidProducts,
//                    'numberCreatedProducts' => $this->importReporter->getNumberCreatedProducts()
//                ]);
//            } else {
//                $this->addFlash('products are valid', 'All products are valid, and successfully created');
//            }
        }
        return $this->render('load/loadFile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}