<?php

namespace App\Controller\Contrat;

use App\Entity\Contrat\ModeRenouvellement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/contrat/mode-renouvellement')]
class ModeRenouvellementController extends AbstractController
{
    #[Route('/', name: 'app_contrat_mode_renouvellement_all', methods: ['GET'])]
    public function all(EntityManagerInterface $manager): Response
    {
        try {
            $modeRenouvellements = $manager->getRepository(ModeRenouvellement::class)->findAll();
            return $this->json(
                array_map(
                    function (ModeRenouvellement $modeRenouvellement) {
                        return [
                            'value' => $modeRenouvellement->getId(),
                            'label' => $modeRenouvellement->getLib(),
                        ];
                    }, $modeRenouvellements),
                Response::HTTP_OK,
                [
                    'Cache-Control' => 'max-age=3600',
                ],
            );
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
