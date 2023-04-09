<?php

namespace App\Entity;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ContratRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Contrat
 *
 */
#[ORM\Entity(repositoryClass: ContratRepository::class)]
#[ORM\Table(name: 'contrat')]
#[ORM\Index(columns: ['id_candidature'], name: 'id_candidature')]
class Contrat
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;
    #[ORM\Column(name: 'typeContrat', type: 'string', length: 100, nullable: true)]
    #[Assert\NotNull]
    private ?string $typecontrat = null;


    #[ORM\Column(name: 'nomCandidat', type: 'string', length: 255, nullable: true)]
    public ?string $nomcondidat = null;

    #[ORM\Column(name: 'dateDebut', type: 'date', nullable: true)]
    #[Assert\NotNull]
    private ?\DateTime $datedebut = null;

    #[ORM\Column(name: 'salaire', type: 'float', length: 15, nullable: true)]
    #[Assert\Positive]
    #[Assert\NotNull]
    private ?float $salaire = null;

    #[ORM\Column(name: 'dateFin', type: 'date', nullable: true)]

    private ?\DateTime $datefin = null;

    #[ORM\Column(name: 'lien', type: 'string', length: 300, nullable: true)]
    #[Assert\NotNull]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    private ?string $lien = null;

    #[ORM\JoinColumn(name: 'id_candidature', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Candidature')]
    private Candidature|null $idCandidature = null;
    #[ORM\Column(name: 'dateCreation', type: 'date', nullable: true)]

    private ?DateTime $datecreation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypecontrat(): ?string
    {
        return $this->typecontrat;
    }

    public function setTypecontrat(?string $typecontrat): self
    {
        $this->typecontrat = $typecontrat;

        return $this;
    }


    public function getNomcandidat(): ?string
    {
        return $this->nomcondidat;
    }

    public function setNoncandidat(?string $nomcandidat): self
    {
        $this->nomcondidat = $nomcandidat;

        return $this;
    }


    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(?\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(?float $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(?string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    public function getIdCandidature(): Candidature
    {
        return $this->idCandidature;
    }

    public function setIdCandidature(Candidature $idCandidature): self
    {
        $this->idCandidature = $idCandidature;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(?\DateTimeInterface $datecreation): self
    {
        $this->datecreation = $datecreation;

        return $this;
    }








}
