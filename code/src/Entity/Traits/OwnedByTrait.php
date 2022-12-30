<?php

namespace App\Entity\Traits;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Account\User;

trait OwnedByTrait
{
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $ownedBy = null;

    public function getOwnedBy(): ?User
    {
        return $this->ownedBy;
    }

    public function setOwnedBy(?User $ownedBy): self
    {
        $this->ownedBy = $ownedBy;

        return $this;
    }
}