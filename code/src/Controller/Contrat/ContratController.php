<?php

namespace App\Controller\Contrat;

use App\Entity\Account\Departement;
use App\Entity\Account\User;
use App\Entity\Contrat\Contrat;
use App\Entity\Contrat\ModeFacturation;
use App\Entity\Contrat\ModePaiement;
use App\Entity\Contrat\ModeRenouvellement;
use App\Entity\Contrat\PeriodicitePaiement;
use App\Entity\Contrat\TypeContrat;
use App\Repository\Account\DepartementRepository;
use App\Service\Contrat\CreateContrat;
use App\Service\Contrat\ListContrat;
use App\Service\Documents\GetContratDocuments;
use App\Service\Utils\SlugTraitToJson;
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

    #[Route('/', name: 'app_contrat_contrat_list', methods: ['GET'])]
    public function list(ListContrat $listContratSrv): JsonResponse
    {
        try {
            return new JsonResponse(($listContratSrv)());
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'Error',
                'errors' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/infos/{id}', name: 'app_contrat_contrat_show', methods: ['GET'])]
    public function show(
        Contrat $contrat,
        SlugTraitToJson $slugTraitToJsonSrv,
        DepartementRepository $departementRepository,
        GetContratDocuments $getContratDocumentsSrv
    ): JsonResponse
    {
        try {
            /** @var User $user */
            $user = $this->getUser();
            // Récupération des éléments de l'entité
            $dataTypeContrat = $slugTraitToJsonSrv($user, TypeContrat::class, true, true);
            $modeFacturation = $slugTraitToJsonSrv($user, ModeFacturation::class, true, true);
            $periodicitePaiement = $slugTraitToJsonSrv($user, PeriodicitePaiement::class, true, true);
            $modePaiement = $slugTraitToJsonSrv($user, ModePaiement::class, true, true);
            $modeRenouvellement = $slugTraitToJsonSrv($user, ModeRenouvellement::class, true, true);

            // On parcours les données pour marquer le choix de l'utilisateur
            array_walk($dataTypeContrat, fn (&$item) => $item['selected'] = $item['value'] === $contrat->getTypeContrat()->getId());
            array_walk($modeFacturation, fn (&$item) => $item['selected'] = $item['value'] === $contrat->getModeFacturation()->getId());
            array_walk($periodicitePaiement, fn (&$item) => $item['selected'] = $item['value'] === $contrat->getPeriodicitePaiement()->getId());
            array_walk($modePaiement, fn (&$item) => $item['selected'] = $item['value'] === $contrat->getModePaiement()->getId());
            array_walk($modeRenouvellement, fn (&$item) => $item['selected'] = $item['value'] === $contrat->getModeRenouvellement()->getId());

            $departements = $departementRepository->findAll();
            $departements = array_map(fn (Departement $departement) => [
                'value' => $departement->getId(),
                'label' => $departement->getNom(),
                'selected' => $departement->getId() === $contrat->getDepartementInitiateur()->getId()
            ], $departements);

            return new JsonResponse(
                array_merge($contrat->toSimpleArray(),
                    ['departementInitiateur' => $departements],
                    ['object' => $contrat->getObjet()],
                    ['typeContrat' => $dataTypeContrat],
                    ['modeFacturation' => $modeFacturation],
                    ['modeRenouvellement' => $modeRenouvellement],
                    ['periodicitePaiement' => $periodicitePaiement],
                    ['modePaiement' => $modePaiement],
                    ['clausesParticulieres' => $contrat->getClausesParticulieres()],
                    ['objetModification' => $contrat->getObjetConditionsModifications()],
                    ['detailsModification' => $contrat->getDetailsConditionsModifications()],
                    ['documents' => $getContratDocumentsSrv($contrat)]
                )
            );
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'Error',
                'errors' => $e->getMessage() . ' ' . $e->getTraceAsString(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
