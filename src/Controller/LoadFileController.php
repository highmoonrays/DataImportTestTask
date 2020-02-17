<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\FIleUploadType;
use App\Service\Uploader\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoadFileController extends AbstractController
{
    /**
     * @Route("/uploadFile", name="upload")
     * @param string $uploadDir
     * @param FileUploader $uploader
     * @param Request $request
     * @return Response
     */
    public function uploadFile(
        string $uploadDir,
        FileUploader $uploader,
        Request $request
    )
    {
        $form = $this->createForm(FIleUploadType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $file = $form->get('file')->getData();
            $filename = $file->getClientOriginalName();
            $uploader->upload($uploadDir, $file, $filename);
            return new Response("File uploaded",  Response::HTTP_OK,
                ['content-type' => 'text/plain']);
        }
        return $this->render('load/loadFile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}