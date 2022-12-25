<?php

namespace App\Controller\Contrat;

use App\Entity\Contrat\ModePaiement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/contrat/mode-paiement')]
class ModePaiementController extends AbstractController
{
    #[Route('/', name: 'app_contrat_mode_paiement_all', methods: ['GET'])]
    public function all(EntityManagerInterface $manager): Response
    {
        try {
            $modePaiements = $manager->getRepository(ModePaiement::class)->findAll();
            return $this->json(
                array_map(
                    function (ModePaiement $modePaiement) {
                        return [
                            'value' => $modePaiement->getId(),
                            'label' => $modePaiement->getLib(),
                        ];
                    }, $modePaiements),
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
