<?php

namespace App\Entity\Traits;

use App\Entity\Account\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait StatutTrait
{
    use SlugTrait;

    #[Groups(['read'])]
    #[ORM\Column(length: 255)]
    private ?string $lib = null;

    #[Groups(['read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $color = null;

    #[ORM\Column(length: 255)]
    private ?array $users = [];

    #[ORM\Column(length: 255)]
    private ?bool $isListable = false;

    /**
     * @return string|null
     */
    public function getLib(): ?string
    {
        return $this->lib;
    }

    /**
     * @param string|null $lib
     */
    public function setLib(?string $lib): self
    {
        $this->lib = $lib;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     */
    public function setColor(?string $color): self
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getUsers(): ?array
    {
        return $this->users;
    }

    /**
     * @param array|null $users
     */
    public function setUsers(?array $users): self
    {
        $this->users = $users;
        return $this;
    }

    public function addUser(User $user): self
    {
        $this->users[] = $user->getId();
        return $this;
    }

    public function removeUser($user): self
    {
        // remove entity in array of entities, compare with id
        $this->users = array_filter($this->users, function ($item) use ($user) {
            return $item != $user->getId();
        });
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsListable(): ?bool
    {
        return $this->isListable;
    }

    /**
     * @param bool|null $isListable
     */
    public function setIsListable(?bool $isListable): void
    {
        $this->isListable = $isListable;
    }
}