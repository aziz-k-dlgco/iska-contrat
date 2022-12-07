<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ZZController extends AbstractController
{
    #[Route('/{name}', name: 'app_no_route', methods: ['GET'])]
    public function index(string $name): Response
    {
        return $this->render('home/index.html.twig');
    }
}
