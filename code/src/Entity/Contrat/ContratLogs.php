<?php

namespace App\Entity\Contrat;

use Andante\TimestampableBundle\Timestampable\CreatedAtTimestampableInterface;
use Andante\TimestampableBundle\Timestampable\CreatedAtTimestampableTrait;
use App\Entity\Account\User;
use App\Repository\Contrat\ContratLogsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

// Entité qui permet de stocker les logs des transitions des contrats
#[ORM\Table(name: 't_contrat_logs')]
#[ORM\Entity(repositoryClass: ContratLogsRepository::class)]
class ContratLogs implements CreatedAtTimestampableInterface
{
    use CreatedAtTimestampableTrait;

    const NEUTRAL_COLOR ="bg-sky-100 text-sky-600";
    const SUCCESS_COLOR = "bg-emerald-100 text-emerald-600";
    const SUCCESS_COLOR_FINAL = "bg-amber-100 text-amber-600";
    const FAILED_COLOR = "bg-rose-100 text-rose-600";

    public const COLORS = [
        'submit' => self::NEUTRAL_COLOR,
        'submit_by_juridique' => self::SUCCESS_COLOR_FINAL,
        'approve_manager' => self::SUCCESS_COLOR,
        'approve_legal_department' => self::SUCCESS_COLOR,
        'reassign_to_agent' => self::SUCCESS_COLOR,
        'approve_agent' => self::SUCCESS_COLOR_FINAL,
        'reject_manager' => self::FAILED_COLOR,
        'reject_legal_department' => self::FAILED_COLOR,
        'reject_agent' => self::FAILED_COLOR,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Transition effectuée
    #[ORM\Column(length: 255)]
    private ?string $transition = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    // Description de la transition
    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    // Contrat concerné
    #[ORM\ManyToOne(inversedBy: 'logs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contrat $contrat = null;

    // Utilisateur qui a effectué la transition
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransition(): ?string
    {
        return $this->transition;
    }

    public function setTransition(string $transition): self
    {
        $this->transition = $transition;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(?Contrat $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
