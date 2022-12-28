<?php

namespace App\Controller\Contrat;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/contrat')]
class ContratController extends AbstractController
{
    #[Route('/new', name: 'app_contrat_contrat_new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        try {
            dump($request->request, gettype($request->request));
            dump($request->files, gettype($request->files));
            return new JsonResponse(['status' => 'ok']);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'Error', 'errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
