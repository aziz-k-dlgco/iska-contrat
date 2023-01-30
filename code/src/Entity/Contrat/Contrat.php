<?php

namespace App\Entity\Contrat;

use Andante\TimestampableBundle\Timestampable\TimestampableInterface;
use Andante\TimestampableBundle\Timestampable\TimestampableTrait;
use App\Entity\Account\Departement;
use App\Entity\Traits\OwnedByTrait;
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
    use TimestampableTrait, OwnedByTrait;

    const CREATED = "created";
    const DEFAULT_TEXT = "Non renseigné";

    const PENDING_MANAGER_APPROVAL = "pending_manager_approval";

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

    #[ORM\Column(length: 255)]
    private ?string $currentState = null;

    #[ORM\OneToMany(mappedBy: 'contrats', targetEntity: Document::class, cascade: ['persist'])]
    private Collection $documents;

    #[ORM\ManyToOne(inversedBy: 'contrats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ModeFacturation $modeFacturation = null;

    #[ORM\ManyToOne(inversedBy: 'contrats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ModePaiement $modePaiement = null;

    #[ORM\ManyToOne(inversedBy: 'contrats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ModeRenouvellement $modeRenouvellement = null;

    #[ORM\ManyToOne(inversedBy: 'contrats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PeriodicitePaiement $periodicitePaiement = null;

    #[ORM\ManyToOne(inversedBy: 'contrats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeContrat $typeContrat = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Departement $departementInitiateur = null;

    #[ORM\OneToMany(mappedBy: 'contrat', targetEntity: ContratLogs::class, cascade: ['persist'])]
    private Collection $logs;

    #[ORM\OneToOne(mappedBy: 'contrat', cascade: ['persist', 'remove'])]
    private ?ContratValidation $validation = null;

    public function toSimpleArray(){
        return [
            'id' => $this->getId(),
            'object' => $this->getObjet(),
            'ownedBy' => $this->getOwnedBy()->getLib(),
            'identiteCocontractant' => $this->getIdentiteConcontractant(),
            'dateEntreeVigueur' => $this->getDateEntreeVigueur()->format('d/m/Y'),
            'dateFinContrat' => $this->getDateFinContrat()->format('d/m/Y'),
            'delaiDenonciation' => $this->getDelaiDenonciationPreavis() . ' mois',
            'currentState' => $this->getCurrentState(),
            'modeFacturation' => $this->getModeFacturation()->getLib(),
            'modePaiement' => $this->getModePaiement()->getLib(),
            'modeRenouvellement' => $this->getModeRenouvellement()->getLib(),
            'periodicitePaiement' => $this->getPeriodicitePaiement()->getLib(),
            'typeContrat' => $this->getTypeContrat()->getLib(),
            'departementInitiateur' => $this->getDepartementInitiateur()->getNom(),
            'createdAt' => $this->getCreatedAt()->format('d/m/Y'),
        ];
    }

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->logs = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = empty($objet) ? self::DEFAULT_TEXT : $objet;

        return $this;
    }

    public function getIdentiteConcontractant(): ?string
    {
        return $this->identiteConcontractant;
    }

    public function setIdentiteConcontractant(string $identiteConcontractant): self
    {
        $this->identiteConcontractant = empty($identiteConcontractant) ? self::DEFAULT_TEXT : $identiteConcontractant;

        return $this;
    }

    public function getClausesParticulieres(): ?string
    {
        return $this->clausesParticulieres;
    }

    public function setClausesParticulieres(string $clausesParticulieres): self
    {
        $this->clausesParticulieres = empty($clausesParticulieres) ? self::DEFAULT_TEXT : $clausesParticulieres;

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
        $this->objetConditionsModifications = empty($objetConditionsModifications) ? self::DEFAULT_TEXT : $objetConditionsModifications;

        return $this;
    }

    public function getDetailsConditionsModifications(): ?string
    {
        return $this->detailsConditionsModifications;
    }

    public function setDetailsConditionsModifications(string $detailsConditionsModifications): self
    {
        $this->detailsConditionsModifications = empty($detailsConditionsModifications) ? self::DEFAULT_TEXT : $detailsConditionsModifications;

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

    public function getModeFacturation(): ?ModeFacturation
    {
        return $this->modeFacturation;
    }

    public function setModeFacturation(?ModeFacturation $modeFacturation): self
    {
        $this->modeFacturation = $modeFacturation;

        return $this;
    }

    public function getModePaiement(): ?ModePaiement
    {
        return $this->modePaiement;
    }

    public function setModePaiement(?ModePaiement $modePaiement): self
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    public function getModeRenouvellement(): ?ModeRenouvellement
    {
        return $this->modeRenouvellement;
    }

    public function setModeRenouvellement(?ModeRenouvellement $modeRenouvellement): self
    {
        $this->modeRenouvellement = $modeRenouvellement;

        return $this;
    }

    public function getPeriodicitePaiement(): ?PeriodicitePaiement
    {
        return $this->periodicitePaiement;
    }

    public function setPeriodicitePaiement(?PeriodicitePaiement $periodicitePaiement): self
    {
        $this->periodicitePaiement = $periodicitePaiement;

        return $this;
    }

    public function getTypeContrat(): ?TypeContrat
    {
        return $this->typeContrat;
    }

    public function setTypeContrat(?TypeContrat $typeContrat): self
    {
        $this->typeContrat = $typeContrat;

        return $this;
    }

    public function getCurrentState(): ?string
    {
        return $this->currentState;
    }

    public function setCurrentState(string $currentState): self
    {
        $this->currentState = $currentState;

        return $this;
    }

    public function getDepartementInitiateur(): ?Departement
    {
        return $this->departementInitiateur;
    }

    public function setDepartementInitiateur(?Departement $departementInitiateur): self
    {
        $this->departementInitiateur = $departementInitiateur;

        return $this;
    }

    /**
     * @return Collection<int, ContratLogs>
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(ContratLogs $log): self
    {
        if (!$this->logs->contains($log)) {
            $this->logs->add($log);
            $log->setContrat($this);
        }

        return $this;
    }

    public function removeLog(ContratLogs $log): self
    {
        if ($this->logs->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getContrat() === $this) {
                $log->setContrat(null);
            }
        }

        return $this;
    }

    public function getValidation(): ?ContratValidation
    {
        return $this->validation;
    }

    public function setValidation(ContratValidation $validation): self
    {
        // set the owning side of the relation if necessary
        if ($validation->getContrat() !== $this) {
            $validation->setContrat($this);
        }

        $this->validation = $validation;

        return $this;
    }
}
