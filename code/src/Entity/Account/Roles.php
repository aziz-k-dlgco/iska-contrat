<?php

namespace App\Entity\Account;

use Andante\TimestampableBundle\Timestampable\TimestampableInterface;
use Andante\TimestampableBundle\Timestampable\TimestampableTrait;
use App\Entity\Traits\SlugTrait;
use App\Repository\Account\RolesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`t_roles`')]
#[ORM\Entity(repositoryClass: RolesRepository::class)]
class Roles implements TimestampableInterface
{
    use TimestampableTrait, SlugTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $lib = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->setSlug($this->getLib());
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLib(): ?string
    {
        return $this->lib;
    }

    public function setLib(string $lib): self
    {
        $this->lib = $lib;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
