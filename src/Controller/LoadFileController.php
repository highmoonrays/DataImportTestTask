<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\FileUploadType;
use App\Service\Uploader\FileUploader;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class LoadFileController extends AbstractController
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
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
        $form = $this->createForm(FileUploadType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('file')->getData();
            $fileName = $file->getClientOriginalName();
            $uploader->upload($uploadDir, $file, $fileName);
            $report = $this->createProduct($uploadDir, $fileName);
            $converter = new AnsiToHtmlConverter();
            $html = $converter->convert($report);
            return new Response($html);
        }

        return $this->render('load/loadFile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/createProduct", name="createProduct")
     * @param $uploadDir
     * @param $fileName
     * @return string
     * @throws \Exception
     */
    public function createProduct($uploadDir, $fileName)
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => 'file:import',
            'path' => $uploadDir.'/'.$fileName,
            '--test-mode' => 'test-mode',
        ));

        $output = new BufferedOutput(
            OutputInterface::VERBOSITY_NORMAL,
            true
        );
        $application->run($input, $output);

        return $output->fetch();
    }
}