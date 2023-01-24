<?php

namespace App\Service\Contrat;

use App\Entity\Account\User;
use App\Entity\Contrat\Contrat;
use Symfony\Component\Security\Core\Security;

class ContratPerms
{
    public function __construct(
        private Security $security
    )
    {
    }

    private function pending_manager_approval(Contrat $contrat, User $user)
    {
        // Si l'utilisateur est le créateur du contrat
        if($contrat->getOwnedBy() === $user){
            $crud_actions['edit'] = true;
            $crud_actions['remove'] = true;

            return [$crud_actions, []];
        }
    }

    public function __invoke(Contrat $contrat, User $user = null): array
    {
        // Settin default values
        $possible_actions = [
            'pending_manager_approval' => false,
            'pending_legal_department_manager_approval' => false,
            'pending_agent_approval' => false,
        ];

        $crud_actions = [
            'edit' => false,
            'remove' => false,
        ];

        try{
            $crud_actions_res = [];
            $possible_actions_res = [];
            // if user is null, use current user
            /** @var User $user */
            $user = $user ?? $this->security->getUser();

            // Checking user rights
            switch ($contrat->getCurrentState()){
                case Contrat::PENDING_MANAGER_APPROVAL:
                    [$crud_actions_res, $possible_actions_res] = $this->pending_manager_approval($contrat, $user);
                    break;
            }

            // Merge results
            $crud_actions = array_merge($crud_actions, $crud_actions_res);
            $possible_actions = array_merge($possible_actions, $possible_actions_res);

            return [
                'possible_actions' => $possible_actions,
                'crud_actions' => $crud_actions,
            ];
        }catch (\Exception $e){
            return [
                'possible_actions' => $possible_actions,
                'crud_actions' => $crud_actions,
            ];
        }
    }
}