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
    )
    {
    }

    public function guardReview(GuardEvent $event){
        /** @var User $user */
        $user = $this->security->getUser();
        // TODO : Déplacer les conditions de bloquage de la transition ici.
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.contract_request.guard' => 'guardReview',
        ];
    }
}