<?php

namespace App\EventSubscriber\Contrat;

use App\Entity\Account\User;
use App\Entity\Contrat\Contrat;
use App\Entity\Contrat\ContratLogs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Workflow\Event\CompletedEvent;
use Symfony\Component\Workflow\WorkflowInterface;

class ContratLogsCreationSubscriber implements EventSubscriberInterface
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

        /** @var User $user */
        $user = $this->security->getUser();

        $metadata = $this->contractRequestStateMachine->getMetadataStore()->getTransitionMetadata(
            $event->getTransition()
        );

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