<?php

namespace App\Controller\Contrat;

use App\Entity\Account\User;
use App\Service\Contrat\CreateContrat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/contrat')]
class ContratController extends AbstractController
{
    #[Route('/new', name: 'app_contrat_contrat_new', methods: ['POST'])]
    public function new(Request $request, CreateContrat $createContratSrv): JsonResponse
    {
        try {
            $createContratSrv($request->request->all(), $request->files->all());
            return new JsonResponse(['status' => 'ok']);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'Error',
                'errors' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
