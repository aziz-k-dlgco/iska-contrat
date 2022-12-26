<?php

namespace App\Entity\Contrat;

use Andante\TimestampableBundle\Timestampable\TimestampableInterface;
use Andante\TimestampableBundle\Timestampable\TimestampableTrait;
use App\Entity\Traits\SlugTrait;
use App\Repository\Contrat\ContratRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`t_contrat`')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ContratRepository::class)]
class Contrat implements TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private $id = null;

    #[ORM\Column(length: 255)]
    private ?string $objet = null;

    #[ORM\Column(length: 255)]
    private ?string $identiteConcontractant = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $clausesParticulieres = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateEntreeVigueur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFinContrat = null;

    #[ORM\Column(length: 255)]
    private ?string $objetConditionsModifications = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $detailsConditionsModifications = null;

    #[ORM\Column]
    private ?int $delaiDenonciationPreavis = null;

    #[ORM\OneToMany(mappedBy: 'contrats', targetEntity: Document::class)]
    private Collection $documents;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getIdentiteConcontractant(): ?string
    {
        return $this->identiteConcontractant;
    }

    public function setIdentiteConcontractant(string $identiteConcontractant): self
    {
        $this->identiteConcontractant = $identiteConcontractant;

        return $this;
    }

    public function getClausesParticulieres(): ?string
    {
        return $this->clausesParticulieres;
    }

    public function setClausesParticulieres(string $clausesParticulieres): self
    {
        $this->clausesParticulieres = $clausesParticulieres;

        return $this;
    }

    public function getDateEntreeVigueur(): ?\DateTimeInterface
    {
        return $this->dateEntreeVigueur;
    }

    public function setDateEntreeVigueur(\DateTimeInterface $dateEntreeVigueur): self
    {
        $this->dateEntreeVigueur = $dateEntreeVigueur;

        return $this;
    }

    public function getDateFinContrat(): ?\DateTimeInterface
    {
        return $this->dateFinContrat;
    }

    public function setDateFinContrat(\DateTimeInterface $dateFinContrat): self
    {
        $this->dateFinContrat = $dateFinContrat;

        return $this;
    }

    public function getObjetConditionsModifications(): ?string
    {
        return $this->objetConditionsModifications;
    }

    public function setObjetConditionsModifications(string $objetConditionsModifications): self
    {
        $this->objetConditionsModifications = $objetConditionsModifications;

        return $this;
    }

    public function getDetailsConditionsModifications(): ?string
    {
        return $this->detailsConditionsModifications;
    }

    public function setDetailsConditionsModifications(string $detailsConditionsModifications): self
    {
        $this->detailsConditionsModifications = $detailsConditionsModifications;

        return $this;
    }

    public function getDelaiDenonciationPreavis(): ?int
    {
        return $this->delaiDenonciationPreavis;
    }

    public function setDelaiDenonciationPreavis(int $delaiDenonciationPreavis): self
    {
        $this->delaiDenonciationPreavis = $delaiDenonciationPreavis;

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setContrats($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getContrats() === $this) {
                $document->setContrats(null);
            }
        }

        return $this;
    }
}
