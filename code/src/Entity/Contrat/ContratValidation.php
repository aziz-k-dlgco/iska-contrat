<?php

namespace App\Entity\Contrat;

use Andante\TimestampableBundle\Timestampable\TimestampableInterface;
use Andante\TimestampableBundle\Timestampable\TimestampableTrait;
use App\Entity\Account\User;
use App\Repository\Contrat\ContratValidationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`t_contrat_validation`')]
#[ORM\Entity(repositoryClass: ContratValidationRepository::class)]
class ContratValidation implements TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToOne(inversedBy: 'validation', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contrat $contrat = null;

    #[ORM\Column]
    private ?bool $isHorsDelai = null;

    #[ORM\Column]
    private ?\DateInterval $delai = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isManagerAction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(Contrat $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
    }

    public function isIsHorsDelai(): ?bool
    {
        return $this->isHorsDelai;
    }

    public function setIsHorsDelai(bool $isHorsDelai): self
    {
        $this->isHorsDelai = $isHorsDelai;

        return $this;
    }

    public function getDelai(): ?\DateInterval
    {
        return $this->delai;
    }

    public function setDelai(\DateInterval $delai): self
    {
        $this->delai = $delai;

        return $this;
    }

    public function isIsManagerAction(): ?bool
    {
        return $this->isManagerAction;
    }

    public function setIsManagerAction(?bool $isManagerAction): self
    {
        $this->isManagerAction = $isManagerAction;

        return $this;
    }
}
