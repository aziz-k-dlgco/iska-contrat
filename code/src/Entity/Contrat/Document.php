<?php

namespace App\Entity\Contrat;

use Andante\TimestampableBundle\Timestampable\TimestampableInterface;
use Andante\TimestampableBundle\Timestampable\TimestampableTrait;
use App\Entity\Traits\DocumentTrait;
use App\Repository\Contrat\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;

#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`t_contrat_document`')]
#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document implements TimestampableInterface
{
    use DocumentTrait, TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contrat $contrat = null;

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getFilename(),
            'link' => $this->getLocation(),
            'createdAt' => $this->getCreatedAt(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    #[PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getContrats(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrats(?Contrat $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
    }
}
