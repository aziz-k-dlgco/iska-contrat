<?php

namespace App\Controller\Contrat;

use App\Entity\Account\User;
use App\Entity\Contrat\ModeRenouvellement;
use App\Entity\Contrat\PeriodicitePaiement;
use App\Service\Utils\SlugTraitToJson;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/contrat/periodicite-paiement')]
class PeriodicitePaiementController extends AbstractController
{
    #[Route('/', name: 'app_contrat_periodicite_paiement_all', methods: ['GET'])]
    public function all(SlugTraitToJson $slugTraitToJsonSrv): Response
    {
        try {
            /** @var User $user */
            $user = $this->getUser();
            return $slugTraitToJsonSrv($user, PeriodicitePaiement::class);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
