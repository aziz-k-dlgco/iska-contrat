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
            dump('hh');
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
            dump('oo');
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
        dump(gettype($data));
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
                ],
                'object' => [
                    'title' => "Objet",
                    'display' => false,
                ],
                'ownedBy' => [
                    'title' => "Créé par",
                    'display' => true,
                ],
                'createdAt' => [
                    'title' => "Créé le",
                    'display' => true,
                ],
                'typeContrat' => [
                    'title' => "Type de contrat",
                    'display' => true,
                ],
                'departementInitiateur' => [
                    'title' => "Département initiateur",
                    'display' => true,
                ],
                'modeFacturation' => [
                    'title' => "Mode de facturation",
                    'display' => true,
                ],
                'modePaiement' => [
                    'title' => "Mode de paiement",
                    'display' => true,
                ],
                'currentState' => [
                    'title' => "Statut",
                    'display' => true,
                ],
                'identiteCocontractant' => [
                    'title' => "Cocontractant",
                    'display' => false,
                ],
                'dateEntreeVigueur' => [
                    'title' => "Date d'entrée en vigueur",
                    'display' => false,
                ],
                'dateFinContrat' => [
                    'title' => "Date de fin",
                    'display' => false,
                ],
                'delaiDenonciation' => [
                    'title' => "Délai de dénonciation",
                    'display' => false,
                ],
                'modeRenouvellement' => [
                    'title' => "Mode de renouvellement",
                    'display' => false,
                ],
                'periodicitePaiement' => [
                    'title' => "Périodicité de paiement",
                    'display' => false,
                ],
            ];
    }

    private function getFilters()
    {
        return [];
    }
}