<?php

namespace App\Controller\Contrat;

use App\Entity\Account\User;
use App\Entity\Contrat\ModeFacturation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/contrat/mode-facturation')]
class ModeFacturationController extends AbstractController
{
    #[Route('/', name: 'app_contrat_mode_facturation_all', methods: ['GET'])]
    public function all(EntityManagerInterface $manager): Response
    {
        try {
            /** @var User $user */
            $user = $this->getUser();
            dump($user);
            $modeFacturations = $manager->getRepository(ModeFacturation::class)->findAll();
            return $this->json(
                array_map(
                    function (ModeFacturation $modeFacturation) {
                        return [
                            'value' => $modeFacturation->getId(),
                            'label' => $modeFacturation->getLib(),
                        ];
                    }, $modeFacturations),
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
