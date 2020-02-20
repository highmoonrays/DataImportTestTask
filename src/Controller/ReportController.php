<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    /**
     * @Route("/report", name="report", methods={"POST"})
     * @param MessageBusInterface $bus
     * @return Response
     */
    public function publish(MessageBusInterface $bus)
    {
        $update = new Update('http://localhost:8000/createProductFromUploadedFile', "nennenenenen");
        $bus->dispatch($update);
        return $this->render('load/report.html.twig');
    }
}
