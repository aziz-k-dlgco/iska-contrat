<?php


namespace App\Entity\Account;

use Andante\TimestampableBundle\Timestampable\CreatedAtTimestampableInterface;
use Andante\TimestampableBundle\Timestampable\CreatedAtTimestampableTrait;
use App\Repository\Account\NotificationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Table(
    name: '`t_notifications`',
    // Create a unique index on the objectType, objectId and id columns
    // This will allow us to query for all notifications for a given object
    // and to query for the latest notification for a given object
    uniqueConstraints: [
        new ORM\UniqueConstraint(
            name: 'object_type_object_id_id',
            columns: ['object_type', 'object_id', 'id']
        )
    ],
)]
#[ORM\Entity(repositoryClass: NotificationsRepository::class)]
class Notifications implements CreatedAtTimestampableInterface
{
    const OBJECT_TYPE_CONTRAT = 'contrat';

    const LIB_TYPE_CONTRAT = [
        'contrat' => 'Demande de contrat',
    ];

    use CreatedAtTimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(length: 255)]
    private ?string $link = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $objectType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $objectId = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => self::LIB_TYPE_CONTRAT[$this->getObjectType()],
            'text' => $this->getText(),
            'link' => $this->getLink(),
            'objectType' => $this->getObjectType(),
            'objectId' => $this->getObjectId(),
            // Date format: Fev 12, 2021
            'createdAt' => $this->getCreatedAt()->format('M d, Y'),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getObjectType(): ?string
    {
        return $this->objectType;
    }

    public function setObjectType(string $objectType): self
    {
        $this->objectType = $objectType;

        return $this;
    }

    public function getObjectId(): ?string
    {
        return $this->objectId;
    }

    public function setObjectId(string $objectId): self
    {
        $this->objectId = $objectId;

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
}
