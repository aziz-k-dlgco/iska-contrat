<?php

namespace App\Entity\Contrat;

use Andante\TimestampableBundle\Timestampable\TimestampableInterface;
use Andante\TimestampableBundle\Timestampable\TimestampableTrait;
use App\Entity\Traits\SlugTrait;
use App\Entity\Traits\StatutTrait;
use App\Repository\Contrat\ModeFacturationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`t_contrat_mode_facturation`')]
#[ORM\Entity(repositoryClass: ModeFacturationRepository::class)]
class ModeFacturation implements TimestampableInterface
{
    /**
     * @uses TimestampableTrait, StatutTrait
     */
    use TimestampableTrait, StatutTrait;
    // Is all entities available to all users
    private const IS_USER_LIMITED = false;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'modeFacturation', targetEntity: Contrat::class)]
    private Collection $contrats;

    public function __construct()
    {
        $this->contrats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->setSlug($this->lib);
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * @return Collection<int, Contrat>
     */
    public function getContrats(): Collection
    {
        return $this->contrats;
    }

    public function addContrat(Contrat $contrat): self
    {
        if (!$this->contrats->contains($contrat)) {
            $this->contrats->add($contrat);
            $contrat->setModeFacturation($this);
        }

        return $this;
    }

    public function removeContrat(Contrat $contrat): self
    {
        if ($this->contrats->removeElement($contrat)) {
            // set the owning side to null (unless already changed)
            if ($contrat->getModeFacturation() === $this) {
                $contrat->setModeFacturation(null);
            }
        }

        return $this;
    }
}
