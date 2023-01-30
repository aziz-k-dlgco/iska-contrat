<?php

namespace App\Service\Contrat;

use App\Entity\Account\User;
use App\Entity\Contrat\Contrat;
use App\Service\Account\GetUsersJuridique;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Workflow\WorkflowInterface;

class ContratPerms
{
    public function __construct(
        private Security $security,
        private GetUsersJuridique $getUsersJuridique,
        private WorkflowInterface $contractRequestStateMachine
    )
    {
    }

    private function getUserRole(Contrat $contrat, User $user): string
    {
        // TODO : Faire un systeme de traits pour enregistrer les différents intervenants
        // Si l'utilisateur est le créateur du contrat
        if($contrat->getOwnedBy() === $user){
            $perms = 'owner';
            if($this->getUsersJuridique->checkUserJuridique($user)){
                $perms = 'user_juridique';
            }
            return $perms;
        } elseif (
            $this->security->isGranted('ROLE_MANAGER')
            &&
            $contrat->getOwnedBy()->getDepartement() === $user->getDepartement()
        ){
            $perms = 'manager';
            return $perms;
        } elseif (
            $this->security->isGranted('ROLE_MANAGER_JURIDIQUE')
        ){
            $perms = 'manager_juridique';
            return $perms;
        }elseif (
            $this->security->isGranted('ROLE_USER_JURIDIQUE')
        ) {
            $perms = 'user_juridique';
            return $perms;
        }
        return 'none';
    }

    private function getPerms(Contrat $contrat, User $user)
    {
        $crud_actions = [
            'edit' => false,
            'remove' => false,
        ];

        $possible_actions = [
            'pending_manager_approval' => false,
            'pending_legal_department_manager_approval' => false,
            'pending_agent_approval' => false,
        ];

        $perms = $this->getUserRole($contrat, $user);

        $placeMetaData = $this->contractRequestStateMachine->getMetadataStore()->getPlaceMetadata(
            $contrat->getCurrentState()
        );

        if(isset($placeMetaData['permissions'][$perms])){
            if(!isset($placeMetaData['permissions'][$perms]['none'])){
                $crud_actions = [
                    'edit' => isset($placeMetaData['permissions'][$perms]['edit']) ?? false,
                    'remove' =>  isset($placeMetaData['permissions'][$perms]['remove']) ?? false,
                ];

                $possible_actions = [
                    'pending_manager_approval' => isset($placeMetaData['permissions'][$perms]['approve_manager']) && $contrat->getCurrentState() === 'pending_manager_approval',
                    'pending_legal_department_manager_approval' => isset($placeMetaData['permissions'][$perms]['transfer']) ?? false,
                    'pending_agent_approval' => isset($placeMetaData['permissions'][$perms]['approve']) ?? false,
                ];
            }
        }
        return [$crud_actions, $possible_actions];
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
            // if user is null, use current user
            /** @var User $user */
            $user = $user ?? $this->security->getUser();

            [$crud_actions_res, $possible_actions_res] = $this->getPerms($contrat, $user);

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