<?php

namespace App\Service\Contrat;

use App\Entity\Account\User;
use App\Entity\Contrat\Contrat;
use App\Repository\Contrat\ContratRepository;
use Symfony\Component\Security\Core\Security;

class ListContrat
{
    public function __construct(
        private Security $security,
        private ContratRepository $contratRepository,
        private StatusToLibContrat $statusToLibContrat
    )
    {
    }

    public function __invoke()
    {
        $title = 'Liste des contrats';
        /** @var User $user */
        $user = $this->security->getUser();
        $data =  [];
        switch (true) {
            case $user->hasRole('ROLE_USER'):
                $data =  array_map(fn(Contrat $contrat) => $contrat->toSimpleArray(), $this->contratRepository->listByOwnedBy($user));
                // apply libContrat filter on currentState field
                array_walk($data, fn(&$item) => $item['currentState'] = ($this->statusToLibContrat)($item['currentState']));
            break;
        }

        return [
            'title' => $title,
            'headers' => $this->getHeaders(),
            'data' => $data
        ];
    }

    private function getHeaders(){
        return [
                'id' => [
                    'title' => "Identifiant",
                    'display' => true,
                ],
                'object' => [
                    'title' => "Objet",
                    'display' => false,
                ],
                'ownedBy' => [
                    'title' => "Créé par",
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
}