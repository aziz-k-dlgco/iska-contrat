<?php

namespace App\Service\Contrat;

use App\Entity\Account\User;
use App\Entity\Contrat\Contrat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Workflow\WorkflowInterface;

class ContratUpdateState
{
    public function __construct(
        private Security $security,
        private ContratPerms $contratPermsSrv,
        private EntityManagerInterface $entityManager,
        private WorkflowInterface $contractRequestStateMachine
    )
    {
    }

    public function __invoke(Contrat $contrat, string $actionToExecute, User $user = null) : array
    {
        try{
            // If user is null get from security service
            /** @var User $user */
            $user = $user ?? $this->security->getUser();

            if(!$this->contractRequestStateMachine->can($contrat, $actionToExecute)){
                throw new \Exception("Not allowed to execute action");
            }

            // Get possible actions and perms
            $possible_actions = (($this->contratPermsSrv)($contrat, $user))['possible_actions'];

            if(isset($possible_actions[$contrat->getCurrentState()])){
                if(!$possible_actions[$contrat->getCurrentState()] ?? true){
                    throw new \Exception("Not allowed to execute action");
                }
            }

            $this->contractRequestStateMachine->apply($contrat, $actionToExecute);

            dump($contrat);

            return [
                'res' => true,
                'message' => 'Action réalisée avec succès'
            ];
        }catch (\Exception $e){
            return [
                'res' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}