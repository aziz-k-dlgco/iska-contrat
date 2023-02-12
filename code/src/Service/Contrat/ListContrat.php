<?php

namespace App\Service\Contrat;

use App\Entity\Account\User;
use App\Entity\Contrat\Contrat;
use App\Entity\Contrat\ContratValidation;
use App\Repository\Contrat\ContratRepository;
use App\Repository\Contrat\ContratValidationRepository;
use App\Service\Account\CheckUserRole;
use Symfony\Component\Security\Core\Security;

class ListContrat
{
    public function __construct(
        private Security $security,
        private ContratRepository $contratRepository,
        private ContratValidationRepository $contratValidationRepository,
        private StatusToLibContrat $statusToLibContrat,
        private CheckUserRole $checkUserRole,
    )
    {
    }

    public function __invoke(User $user = null)
    {
        $title = 'Liste des contrats';
        /** @var User $user */
        $user = $user ?? $this->security->getUser();
        $data =  [];

        // Récupération des contrats initiés par l'utilisateur
        if(($this->checkUserRole)($user, 'ROLE_USER')){
            $data = array_merge(
                $data,
                array_map(
                    fn(Contrat $contrat) => $contrat->toSimpleArray(),
                    $this->contratRepository->listByOwnedBy($user)
                )
            );
        }

        // Récupération des demandes de contrat en attente de validation par l'utilisateur juridique
        if(($this->checkUserRole)($user, 'ROLE_USER_JURIDIQUE')){
            $data = array_merge(
                $data,
                array_map(
                    fn(ContratValidation $contratValidation) => $contratValidation->getContrat()->toSimpleArray(),
                    // Utilisation du système de validation pour aller chercher le contrat
                    $this->contratValidationRepository->findBy([
                        'user' => $user,
                    ])
                )
            );
        }

        // Récupération des contrats de département du manager de département
        if(($this->checkUserRole)($user, 'ROLE_MANAGER')){
            $data = array_merge(
                $data,
                array_map(
                    fn(Contrat $contrat) => $contrat->toSimpleArray(),
                    $this->contratRepository->findBy([
                        'departementInitiateur' => $user->getDepartement()
                    ])
                )
            );
        }

        // Récupération des contrats du manager juridique
        if(($this->checkUserRole)($user, 'ROLE_MANAGER_JURIDIQUE')){
            // Ayant déja récupéré ses contrats initiés, ceux qu'il a traités et ceux de son département, il ne reste que les demandes en attente de validation
            $data = array_merge(
                $data,
                array_map(
                    fn(Contrat $contrat) => $contrat->toSimpleArray(),
                    $this->contratRepository->findBy([
                        'currentState' => [
                            'pending_legal_department_manager_approval',
                            'pending_agent_approval',
                            'approved',
                            'rejected_by_legal_department_manager',
                            'rejected_by_agent'
                        ]
                    ])
                )
            );
        }

        // Suppression des doublons
        $data = array_unique($data, SORT_REGULAR);
        // Suppression des tableaux vides
        $data = array_filter($data);
        // Si data est un tableau associatif, on le transforme en tableau simple
        if(array_keys($data) !== range(0, count($data) - 1)){
            $data = array_values($data);
        }

        // apply libContrat filter on currentState field
        array_walk($data, fn(&$item) => $item['currentState'] = ($this->statusToLibContrat)($item['currentState']));
        array_walk($data, fn(&$item) => $item['link'] = '/contrat/consult/' . $item['id']);
        return [
            'title' => $title,
            'headers' => $this->getHeaders(),
            'data' => $data,
            'filters' => $this->getFilters(),
        ];
    }

    private function getHeaders(){
        return [
                'id' => [
                    'title' => "Identifiant",
                    'display' => false,
                    'type' => 'string',
                    'order' => 'ASC'
                ],
                'object' => [
                    'title' => "Objet",
                    'display' => false,
                    'type' => 'string',
                    'order' => 'ASC'
                ],
                'ownedBy' => [
                    'title' => "Créé par",
                    'display' => true,
                    'type' => 'string',
                    'order' => 'ASC'
                ],
                'createdAt' => [
                    'title' => "Créé le",
                    'display' => true,
                    'type' => 'date',
                    'order' => 'ASC'
                ],
                'typeContrat' => [
                    'title' => "Type de contrat",
                    'display' => true,
                    'type' => 'string',
                    'order' => 'ASC'
                ],
                'departementInitiateur' => [
                    'title' => "Département initiateur",
                    'display' => true,
                    'type' => 'string',
                    'order' => 'ASC'
                ],
                'modeFacturation' => [
                    'title' => "Mode de facturation",
                    'display' => true,
                    'type' => 'string',
                    'order' => 'ASC'
                ],
                'modePaiement' => [
                    'title' => "Mode de paiement",
                    'display' => true,
                    'type' => 'string',
                    'order' => 'ASC'
                ],
                'currentState' => [
                    'title' => "Statut",
                    'display' => true,
                    'type' => 'object',
                    'order' => 'ASC'
                ],
                'identiteCocontractant' => [
                    'title' => "Cocontractant",
                    'display' => false,
                    'type' => 'string',
                    'order' => 'ASC'
                ],
                'dateEntreeVigueur' => [
                    'title' => "Date d'entrée en vigueur",
                    'display' => false,
                    'type' => 'date',
                    'order' => 'ASC'
                ],
                'dateFinContrat' => [
                    'title' => "Date de fin",
                    'display' => false,
                    'type' => 'date',
                    'order' => 'ASC'
                ],
                'delaiDenonciation' => [
                    'title' => "Délai de dénonciation",
                    'display' => false,
                    'type' => 'string',
                    'order' => 'ASC'
                ],
                'modeRenouvellement' => [
                    'title' => "Mode de renouvellement",
                    'display' => false,
                    'type' => 'string',
                    'order' => 'ASC'
                ],
                'periodicitePaiement' => [
                    'title' => "Périodicité de paiement",
                    'display' => false,
                    'type' => 'string',
                    'order' => 'ASC'
                ],
            ];
    }

    private function getFilters()
    {
        return [];
    }
}