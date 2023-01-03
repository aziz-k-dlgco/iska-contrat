<?php

namespace App\EventSubscriber;

use App\Entity\Account\User;
use App\Entity\Contrat\Contrat;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\Workflow\WorkflowInterface;

class ContractSubmitSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private WorkflowInterface $contractRequestStateMachine
    )
    {
    }

    public function onWorkflowContractRequestGuardSubmit(GuardEvent $event): void
    {
        /** @var User $user */
        $user = $this->security->getUser();
        /** @var Contrat $contractRequest */
        $contractRequest = $event->getSubject();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.contract_request.guard.submit' => 'onWorkflowContractRequestGuardSubmit',
        ];
    }
}
