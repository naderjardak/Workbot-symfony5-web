<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\OffreRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Offre
 *
 * @ORM\Entity
 * @ORM\Table(name="offre")
 */
#[ORM\Entity(repositoryClass: OffreRepository::class)]
#[ORM\Table(name: 'offre')]
#[ORM\Index(columns: ['id_Soc'], name: 'offre_ibfk_1')]
#[ORM\Index(columns: ['id_test'], name: 'offre_ibfk_2')]
class Offre
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\OneToMany(targetEntity: 'Reclamation')]
    private int $id;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'le titre doit contenir au moins 2 caractere',
        maxMessage: 'le titre doit contenir au maximum 50 caractere'
    )]
    #[ORM\Column(name: 'titre', type: 'string', length: 300, nullable: true)]
    private ?string $titre = null;

    #[Assert\Length(
        min: 8,
        max: 50,
        minMessage: 'ce champs doit contenir au moins 8 caractere',
        maxMessage: 'le titre doit contenir au maximum 50 caractere'
    )]
    #[ORM\Column(name: 'salaire', type: 'string', length: 255, nullable: true)]
    private ?string $salaire = null;

    #[ORM\Column(name: 'description', type: 'string', length: 250, nullable: true)]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column(name: 'domaine', type: 'string', length: 200, nullable: true)]
    private ?string $domaine = null;

    #[Assert\NotBlank]
    #[Assert\Date]
    #[ORM\Column(name: 'dateExpiration', type: 'string', length: 200, nullable: true)]
    private ?string $dateexpiration = null;

    #[Assert\Length(
        min: 1,
        max: 50,
        minMessage: 'la durée doit etre valide',
        maxMessage: 'la durée doit etre valide'
    )]
    #[ORM\Column(name: 'dureeStage', type: 'string', length: 200, nullable: true)]
    private ?string $dureestage = null;

    #[Assert\Choice(['PFE', "Stage d'été", 'Alternance'])]
    #[ORM\Column(name: 'typeStage', type: 'string', length: 20, nullable: true)]
    private ?string $typestage = null;

    #[ORM\Column(name: 'dureeContrat', type: 'string', length: 30, nullable: true)]
    private ?string $dureecontrat = null;

    #[ORM\Column(name: 'typeContrat', type: 'string', length: 30, nullable: true)]
    private ?string $typecontrat = null;

    #[ORM\Column(name: 'anneeExperience', type: 'string', length: 30, nullable: true)]
    private ?string $anneeexperience = null;

    #[Assert\NotBlank]
    #[Assert\Choice(['Présentiel', 'Hybrid', 'Teletravail'])]
    #[ORM\Column(name: 'modeTravail', type: 'string', length: 25, nullable: true)]
    private ?string $modetravail = null;

    #[ORM\Column(name: 'lieu', type: 'string', length: 250, nullable: true)]
    private ?string $lieu = null;

    #[ORM\Column(name: 'typeOffre', type: 'string', length: 15, nullable: true)]
    private ?string $typeoffre = null;

    #[ORM\Column(name: 'dateAjout', type: 'string', length: 100, nullable: true)]
    private ?string $dateajout = null;

    #[ORM\JoinColumn(name: 'id_Soc', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Utilisateur')]
    private \App\Entity\Utilisateur $idSoc;

    #[ORM\JoinColumn(name: 'id_test', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Test')]
    private ?Test $Test= null;

    private int $nbCandidature;

    /**
     * @return int
     */
    public function getNbCandidature(): int
    {
        return $this->nbCandidature;
    }

    /**
     * @param int $nbCandidature
     */
    public function setNbCandidature(int $nbCandidature): void
    {
        $this->nbCandidature = $nbCandidature;
    }



    /**
     * @return Utilisateur
     */
    public function getIdSoc(): Utilisateur
    {
        return $this->idSoc;
    }

    /**
     * @param Utilisateur $idSoc
     */
    public function setIdSoc(Utilisateur $idSoc): void
    {
        $this->idSoc = $idSoc;
    }


    public function getTest()
    {
        return $this->Test;
    }

    /**
     * @param Test $Test
     */
    public function setTest(Test $Test): void
    {
        $this->Test = $Test;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSalaire(): ?string
    {
        return $this->salaire;
    }

    public function setSalaire(?string $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(?string $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getDateexpiration(): ?string
    {
        return $this->dateexpiration;
    }

    public function setDateexpiration(?string $dateexpiration): self
    {
        $this->dateexpiration = $dateexpiration;

        return $this;
    }

    public function getDureestage(): ?string
    {
        return $this->dureestage;
    }

    public function setDureestage(?string $dureestage): self
    {
        $this->dureestage = $dureestage;

        return $this;
    }

    public function getTypestage(): ?string
    {
        return $this->typestage;
    }

    public function setTypestage(?string $typestage): self
    {
        $this->typestage = $typestage;

        return $this;
    }

    public function getDureecontrat(): ?string
    {
        return $this->dureecontrat;
    }

    public function setDureecontrat(?string $dureecontrat): self
    {
        $this->dureecontrat = $dureecontrat;

        return $this;
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

    public function getAnneeexperience(): ?string
    {
        return $this->anneeexperience;
    }

    public function setAnneeexperience(?string $anneeexperience): self
    {
        $this->anneeexperience = $anneeexperience;

        return $this;
    }

    public function getModetravail(): ?string
    {
        return $this->modetravail;
    }

    public function setModetravail(?string $modetravail): self
    {
        $this->modetravail = $modetravail;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getTypeoffre(): ?string
    {
        return $this->typeoffre;
    }

    public function setTypeoffre(?string $typeoffre): self
    {
        $this->typeoffre = $typeoffre;

        return $this;
    }

    public function getDateajout(): ?string
    {
        return $this->dateajout;
    }

    public function setDateajout(?string $dateajout): self
    {
        $this->dateajout = $dateajout;

        return $this;
    }



}
