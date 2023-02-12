<?php

namespace App\Service\Contrat;

use App\Entity\Account\User;
use App\Repository\Contrat\ContratRepository;
use App\Repository\Contrat\ContratValidationRepository;
use App\Service\Account\CheckUserRole;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Workflow\WorkflowInterface;

class StatsContrat
{
    public function __construct(
        private Security $security,
        private CheckUserRole $checkUserRole,
        private ContratRepository $contratRepository,
        private ContratValidationRepository $contratValidationRepository,
        private WorkflowInterface $contractRequestStateMachine
    )
    {
    }

    /**
     * Fonction qui retourne le nombre de contrat en fonction de son état
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getStateCounter(string $state): int
    {
        try {
            return $this->contratRepository->countByState($state);
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getStats(User $user = null): array
    {
        if($user === null) {
            /** @var User $user */
            $user = $this->security->getUser();
        }

        if($this->checkUserRole->__invoke($user, 'ROLE_MANAGER_JURIDIQUE')) {
            $stats = [
                [
                    'lib' => "Demandes en attente d'attribution",
                    'description' => "Nombre de demandes de contrat en attente d'attribution à un membre du service juridique",
                    'value' => $this->getStateCounter('pending_legal_department_manager_approval'),
                ],
                [
                    'lib' => "Demandes en attente de validation",
                    'description' => "Nombre de demandes de contrat en attribuées à un membre du service juridique en attente de traitement",
                    'value' => $this->getStateCounter('pending_agent_approval'),

                ],
                [
                    'lib' => "Demandes validées",
                    'description' => "Nombre de demandes de contrat validées et transfomées en contrat",
                    'value' => $this->getStateCounter('approved'),
                ],
                [
                    'lib' => "Contrats arrivant à échéance",
                    'description' => "Nombre de contrats arrivant à échéance dans les 3 mois à venir",
                    'value' => 0,
                ],
            ];
        } elseif ($this->checkUserRole->__invoke($user, 'ROLE_USER_JURIDIQUE')) {
            $stats = [
                [
                    'lib' => "Demandes en attente de validation",
                    'description' => "Nombre de demandes de contrat qui vous sont attribuées en attente de traitement.",
                    'value' => $this->contratValidationRepository->countUserPendingValidation($user),
                ],
                [
                    'lib' => "Demandes en attente de validation hors-délai",
                    'description' => "Nombre de demandes de contrat qui vous sont attribuées en attente de traitement et dont le délai de traitement est dépassé.",
                    'value' => $this->contratValidationRepository->countUserPendingValidation($user, true),
                ],
                [
                    'lib' => "Contrats validés",
                    'description' => "Nombre de contrats que vous avez validés.",
                    'value' => $this->contratValidationRepository->countUserByState($user, 'approved'),
                ],
                [
                    'lib' => "Contrats rejetés",
                    'value' => $this->contratValidationRepository->countUserByState($user, 'rejected_by_agent'),
                ],
            ];
        } elseif ($this->checkUserRole->__invoke($user, 'ROLE_MANAGER')) {
            $stats = [
                [
                    'lib' => "Demandes en attente de validation",
                    'description' => "Nombre de demandes de contrat en attente de validation de votre part.",
                    'value' => count($this->contratRepository->findBy([
                        'currentState' => 'pending_manager_approval',
                        'departementInitiateur' => $user->getDepartement()
                    ])),
                ],
                [
                    'lib' => "Demandes traitées",
                    'description' => "Nombre de demandes de contrat traitées par le service juridique.",
                    'value' => $this->contratValidationRepository->countDepartementValidated($user->getDepartement()),
                ],
                [
                    'lib' => "Contrats arrivant à échéance",
                    'description' => "Nombre de contrats arrivant à échéance dans les 3 mois à venir.",
                    'value' => 0,
                ],
                [
                    'lib' => "Demandes rejetées",
                    'description' => "Nombre de demandes de contrat que vous avez rejetées.",
                    'value' => count($this->contratRepository->findBy([
                        'currentState' => 'rejected_by_manager',
                        'departementInitiateur' => $user->getDepartement()
                    ])),
                ],
            ];
        }else{
            $stats = [
                [
                    'lib' => "Demandes en attente de validation",
                    'description' => "Nombre de demandes de contrat en attente de validation.",
                    'value' => count($this->contratRepository->findBy([
                        'currentState' => 'pending_manager_approval',
                        'departementInitiateur' => $user->getDepartement()
                    ])) + $this->contratValidationRepository->countUserRequestsPendingForValidation($user),
                ],
                [
                    'lib' => "Contrats arrivant à échéance",
                    'description' => "Nombre de contrats arrivant à échéance dans les 3 mois à venir.",
                    'value' => 0,
                ],
                [
                    'lib' => "Demandes validées",
                    'value' => count($this->contratRepository->findBy([
                        'currentState' => 'approved',
                        'ownedBy' => $user
                    ])),
                ],
                [
                    'lib' => "Demandes rejetées",
                    'value' => count($this->contratRepository->findBy([
                        // state can be rejected_by_manager or rejected_by_legal_department_manager or rejected_by_agent
                        'currentState' => ['rejected_by_manager', 'rejected_by_legal_department_manager', 'rejected_by_agent'],
                        'ownedBy' => $user
                    ])),
                ],
            ];
        }

        return $stats;
    }
}