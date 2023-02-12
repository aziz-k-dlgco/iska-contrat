<?php

namespace App\Controller\Contrat;

use App\Entity\Account\Departement;
use App\Entity\Account\User;
use App\Entity\Contrat\Contrat;
use App\Entity\Contrat\ContratLogs;
use App\Entity\Contrat\ModeFacturation;
use App\Entity\Contrat\ModePaiement;
use App\Entity\Contrat\ModeRenouvellement;
use App\Entity\Contrat\PeriodicitePaiement;
use App\Entity\Contrat\TypeContrat;
use App\Repository\Account\DepartementRepository;
use App\Repository\Account\UserRepository;
use App\Repository\Contrat\ContratRepository;
use App\Service\Account\GetUsersJuridique;
use App\Service\Contrat\ContratPerms;
use App\Service\Contrat\ContratUpdateState;
use App\Service\Contrat\CreateContrat;
use App\Service\Contrat\ListContrat;
use App\Service\Contrat\StatsContrat;
use App\Service\Contrat\UpdateContrat;
use App\Service\Documents\GetContratDocuments;
use App\Service\Utils\SlugTraitToJson;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

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
                'errors' => $e->getMessage() . ' ' . $e->getTraceAsString(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/stats', name: 'app_contrat_contrat_stats', methods: ['GET'])]
    public function stats(StatsContrat $statsContratSrv): JsonResponse
    {
        try {
            return new JsonResponse($statsContratSrv->getStats());
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'Error',
                'errors' => $e->getMessage() . ' ' . $e->getTraceAsString(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/infos/{id}', name: 'app_contrat_contrat_show', methods: ['GET'])]
    //throw error if contrat not found
    public function show(
        string $id,
        ContratRepository $contratRepository,
        SlugTraitToJson $slugTraitToJsonSrv,
        DepartementRepository $departementRepository,
        GetContratDocuments $getContratDocumentsSrv,
        ContratPerms $contratPermsSrv
    ): JsonResponse
    {
        try {
            $contrat = $contratRepository->findOneBy(['id' => $id]);
            if(empty($contrat)){
                throw new \Exception('Contrat not found');
            }
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
                    ['documents' => $getContratDocumentsSrv($contrat)],
                    ['perms' => $contratPermsSrv($contrat)],
                    ['logs' => array_map(fn (ContratLogs $log) => $log->toArray(), $contrat->getLogs()->toArray())]
                )
            );
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'Error',
                'errors' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/update_state/{id}', name: 'app_contrat_contrat_update_state', methods: ['PUT'])]
    public function update_state(
        string $id,
        Request $request,
        ContratRepository $contratRepository,
        ContratUpdateState $contratUpdateStateSrv
    ){
        try {
            $contrat = $contratRepository->findOneBy(['id' => $id]);
            if(empty($contrat)){
                throw new \Exception('Contrat not found');
            }

            $data = json_decode($request->getContent(), true);

            return $this->json(
                ($contratUpdateStateSrv)($contrat, $data['action']),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'Error',
                'errors' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/reassign_to_agent', name: 'app_contrat_contrat_assign_to_agent', methods: ['PUT'])]
    public function reassign_to_agent(
        Request $request,
        EntityManagerInterface $entityManager,
        ContratRepository $contratRepository,
        UserRepository $userRepository,
        WorkflowInterface $contractRequestStateMachine,
        GetUsersJuridique $getUsersJuridiqueSrv
    ){
        try {
            $data = json_decode($request->getContent(), true);

            if(empty($data['contrat_id']) || empty($data['user_id'])){
                throw new \Exception('Missing data');
            }

            $contrat = $contratRepository->findOneBy(['id' => $data['contrat_id']]);
            if(empty($contrat)){
                throw new \Exception('Contrat not found');
            }

            $user = $userRepository->findOneBy(['id' => $data['user_id']]);
            if(empty($user)){
                throw new \Exception('User not found');
            }

            if ($getUsersJuridiqueSrv->checkUserJuridique($user) === false) {
                throw new \Exception('User is not juridique');
            }

            if ($contractRequestStateMachine->can($contrat, 'reassign_to_agent')) {
                $contractRequestStateMachine->apply($contrat, 'reassign_to_agent',
                    [
                        'user' => $user,
                    ]
                );
            }

            $entityManager->persist($contrat);
            $entityManager->flush();

            return $this->json(
                [
                    'res' => true,
                    'message' => 'Action réalisée avec succès'
                ],
                Response::HTTP_OK
            );

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'Error',
                'errors' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'app_contrat_contrat_update', methods: ['PUT'])]
    public function update(
        string $id,
        Request $request,
        ContratRepository $contratRepository,
        UpdateContrat $updateContratSrv
    ): JsonResponse
    {
        try {
            $contrat = $contratRepository->findOneBy(['id' => $id]);
            if(empty($contrat)){
                throw new \Exception('Contrat not found');
            }

            // GET JSON DATA
            $data = json_decode($request->getContent(), true);
            $updateContratSrv($contrat, $data);
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
