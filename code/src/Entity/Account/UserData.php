<?php

namespace App\Entity\Account;

use Andante\TimestampableBundle\Timestampable\UpdatedAtTimestampableInterface;
use Andante\TimestampableBundle\Timestampable\UpdatedAtTimestampableTrait;
use App\Repository\Account\UserDataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`t_user_data`')]
#[ORM\Entity(repositoryClass: UserDataRepository::class)]
class UserData implements UpdatedAtTimestampableInterface
{
    use UpdatedAtTimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'additionnalData', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateInterval $delaiTraitementContrat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDelaiTraitementContrat(): ?\DateInterval
    {
        return $this->delaiTraitementContrat;
    }

    public function setDelaiTraitementContrat(\DateInterval $delaiTraitementContrat): self
    {
        $this->delaiTraitementContrat = $delaiTraitementContrat;

        return $this;
    }
}
