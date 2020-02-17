<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\UploadFileToCreateProductType;
use App\Service\Processor\ImportProcessor;
use App\Service\Reporter\FileImportReporter;
use App\Service\Uploader\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/createProductFromUploadedFile", name="createProductFromUploadedFile")
     * @param string $uploadDir
     * @param FileUploader $uploader
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function createProductFromUploadedFile(
        string $uploadDir,
        FileUploader $uploader,
        Request $request): Response
    {
        $form = $this->createForm(UploadFileToCreateProductType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $pathToFile = $uploader->upload($uploadDir, $form);
            $this->importProcessor->process($pathToFile);
            $invalidProducts = $this->importReporter->getInvalidProducts();
            $messages = $this->importReporter->getMessages();

            if(false === $form->get('isTest')->getData()){
                $this->em->flush();
            }

            return $this->render('load/report.html.twig', [
                'numberInvalidProducts' => count($this->importReporter->getInvalidProducts()),
                'messages' => $messages,
                'invalidProducts' => $invalidProducts,
                'numberCreatedProducts' => $this->importReporter->getNumberCreatedProducts()
            ]);
        }

        return $this->render('load/loadFile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}