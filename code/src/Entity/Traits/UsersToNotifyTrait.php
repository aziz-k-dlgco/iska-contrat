<?php

namespace App\Entity\Traits;

use App\Entity\Contrat\Contrat;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Account\User;

trait UsersToNotifyTrait
{
    #[ORM\Column]
    private array $usersToNotify = [];

    /**
     * @return array
     */
    public function getUsersToNotify(): array
    {
        return $this->usersToNotify;
    }

    /**
     * @param User $user
     * @return Contrat|UsersToNotifyTrait
     */
    public function addUsersToNotify(User $user): self
    {
        $this->usersToNotify[] = $user->getId();
        // array_unique() is used to remove duplicate values
        $this->usersToNotify = array_unique($this->usersToNotify);
        return $this;
    }
}