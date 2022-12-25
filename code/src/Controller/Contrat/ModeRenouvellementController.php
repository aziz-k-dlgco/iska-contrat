<?php

namespace App\Controller\Contrat;

use App\Entity\Account\User;
use App\Entity\Contrat\ModeFacturation;
use App\Entity\Contrat\ModeRenouvellement;
use App\Service\Utils\SlugTraitToJson;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/contrat/mode-renouvellement')]
class ModeRenouvellementController extends AbstractController
{
    #[Route('/', name: 'app_contrat_mode_renouvellement_all', methods: ['GET'])]
    public function all(SlugTraitToJson $slugTraitToJsonSrv): Response
    {
        try {
            /** @var User $user */
            $user = $this->getUser();
            return $slugTraitToJsonSrv($user, ModeRenouvellement::class);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
