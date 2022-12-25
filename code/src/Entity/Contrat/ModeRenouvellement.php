<?php

namespace App\Entity\Contrat;

use Andante\TimestampableBundle\Timestampable\TimestampableInterface;
use Andante\TimestampableBundle\Timestampable\TimestampableTrait;
use App\Entity\Traits\SlugTrait;
use App\Entity\Traits\StatutTrait;
use App\Repository\Contrat\ModeRenouvellementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`t_contrat_mode_renouvellement`')]
#[ORM\Entity(repositoryClass: ModeRenouvellementRepository::class)]
class ModeRenouvellement implements TimestampableInterface
{
    /**
     * @uses TimestampableTrait, SlugTrait, StatutTrait
     */
    use TimestampableTrait, SlugTrait, StatutTrait;
    // Is all entities available to all users
    private const IS_USER_LIMITED = true;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

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
}