<?php

namespace App\EventSubscriber\Contrat;

use App\Entity\Account\User;
use App\Entity\Contrat\Contrat;
use App\Service\Contrat\ContratPerms;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\Workflow\WorkflowInterface;

class ContratStartTransitionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private WorkflowInterface $contractRequestStateMachine,
        private ContratPerms $contratPermsSrv,
    )
    {
    }

    public function guardReview(GuardEvent $event){
        // TODO : Déplacer les conditions de bloquage de la transition ici.
        $transitionName = $event->getTransition()->getName();

        /** @var Contrat $contrat */
        $contrat = $event->getSubject();

        // Get possible actions and perms
        $possible_actions = (($this->contratPermsSrv)($contrat, $user))['possible_actions'];

        // get current state
        $current_state = $this->contractRequestStateMachine->getMarking($contrat)->getPlaces()[0];
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.contract_request.guard' => 'guardReview',
        ];
    }
}