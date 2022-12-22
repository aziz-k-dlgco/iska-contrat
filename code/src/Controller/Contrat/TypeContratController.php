<?php

namespace App\Controller\Contrat;

use App\Entity\Contrat\TypeContrat;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/z/contrat/type-contrat')]
class TypeContratController extends AbstractController
{
    #[Route('/', name: 'app_contrat_type_contrat_all', methods: ['GET'])]
    public function all(EntityManagerInterface $manager): Response
    {
        try {
            $typeContrats = $manager->getRepository(TypeContrat::class)->findAll();
            return $this->json($typeContrats, Response::HTTP_OK, [], ['groups' => 'read']);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
