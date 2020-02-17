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

class LoadFileController extends AbstractController
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
     * @var EntityManager
     */
    private $em;

    /**
     * LoadFileController constructor.
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
     * @Route("/uploadFile", name="uploadFile")
     * @param string $uploadDir
     * @param FileUploader $uploader
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function uploadFile(
        string $uploadDir,
        FileUploader $uploader,
        Request $request): Response
    {
        $form = $this->createForm(UploadFileToCreateProductType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('file')->getData();
            $fileName = $file->getClientOriginalName();
            $uploader->upload($uploadDir, $file, $fileName);
            $report = $this->createProduct($uploadDir, $fileName);

            if(false === $form->get('isTest')->getData()){
                $this->em->flush();
            }

            return $this->render('load/report.html.twig', [
                'numberInvalidProducts' => count($report)/2,
                'invalidProducts' => $report,
                'numberCreatedProducts' => $this->importReporter->getNumberCreatedProducts()
            ]);
        }

        return $this->render('load/loadFile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/createProduct", name="createProduct")
     * @param $uploadDir
     * @param $fileName
     * @return array
     * @throws \Exception
     */
    public function createProduct($uploadDir, $fileName): array
    {
        $this->importProcessor->process($uploadDir.'/'.$fileName);

        $invalidProductsReport = [];
        $messages = $this->importReporter->getMessages();

        foreach ($this->importReporter->getInvalidProducts() as $key => $invalidItem) {
            $invalidItem = json_encode($invalidItem);
            $invalidProductsReport[] = $messages[$key];
            $invalidProductsReport[] = $invalidItem;
        }
        return $invalidProductsReport;
    }
}