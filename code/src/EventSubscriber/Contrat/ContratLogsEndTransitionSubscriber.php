<?php

namespace App\EventSubscriber\Contrat;

use App\Entity\Account\User;
use App\Entity\Contrat\Contrat;
use App\Entity\Contrat\ContratLogs;
use App\Entity\Contrat\ContratValidation;
use Carbon\CarbonInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Workflow\Event\CompletedEvent;
use Symfony\Component\Workflow\WorkflowInterface;

class ContratLogsEndTransitionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private WorkflowInterface $contractRequestStateMachine
    )
    {
    }

    public function guardReview(CompletedEvent $event)
    {

        /** @var Contrat $contrat */
        $contrat = $event->getSubject();

        // On récupère l'utilisateur connecté par défaut
        /** @var User $user */
        $user = $this->security->getUser();
        $transitionName = $event->getTransition()->getName();
        // Si la transition est reassign_to_agent, on récupère l'utilisateur qui a été assigné
        if($transitionName === 'reassign_to_agent') {
            /** @var User $user */
            $user = $event->getContext()['user'];
            // On met à jour le délai de traitement
            $contrat->setValidation(
                (new ContratValidation())
                    ->setUser($user)
                    ->setIsHorsDelai(false)
                    ->setDelai(
                        $user
                            ->getAdditionnalData()
                            ->getDelaiTraitementContrat() ?? (CarbonInterval::days(14)->toDateInterval())
                    )
            );
        }
        // Si la transition est reject_agent ou approve_agent, on marque la validation comme faite
        elseif ($transitionName === 'reject_agent' || $transitionName === 'approve_agent'){
            $validation = $contrat->getValidation();
            $validation->setUpdatedAt(new \DateTimeImmutable());
            $contrat->setValidation($validation);
        }
        // Récupération des métadonnées de la transition
        $metadata = $this->contractRequestStateMachine->getMetadataStore()->getTransitionMetadata(
            $event->getTransition()
        );
        // Ajout du log
        $contrat->addLog(
            (new ContratLogs())
                ->setTitle($metadata['title'] ?? 'Changement de statut')
                ->setUser($user)
                ->setTransition($event->getTransition()->getName())
                ->setText(isset($metadata['description']) ? ($metadata['description'] . ' ' . $user->getLib()) :  'No log')
            );

        // TODO : Ajouter un mail de notification
    }
    public static function getSubscribedEvents()
    {
        return [
            'workflow.contract_request.completed' => ['guardReview'],
        ];
    }
}