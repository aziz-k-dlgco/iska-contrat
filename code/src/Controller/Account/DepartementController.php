<?php

namespace App\Controller\Account;

use App\Entity\Account\Departement;
use App\Entity\Account\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/account/departement')]
class DepartementController extends AbstractController
{
    #[Route('/', name: 'app_account_departement_all', methods: ['GET'])]
    public function all(EntityManagerInterface $manager): Response
    {
        try {
            /** @var User $user */
            $user = $this->getUser();

            $departements = $manager->getRepository(Departement::class)->findAll();
            return $this->json(
                array_map(
                    function (Departement $departement) use ($user) {
                        return array_merge(
                            [
                                'value' => $departement->getId(),
                                'label' => $departement->getNom(),
                            ],
                            $departement->getId() === $user->getDepartement()->getId() ? ['selected' => true] : []
                        );
                    }, $departements),
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
