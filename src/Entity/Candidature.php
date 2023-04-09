<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CandidatureRepository;
use Doctrine\ORM\Mapping as ORM;
//candidatures
/**
 * Candidature
 *
 */
#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
#[ORM\Table(name: 'candidature')]
#[ORM\Index(columns: ['idcondidat'], name: 'candidature_ibfk_1')]
#[ORM\Index(columns: ['id_offre'], name: 'candidature_ibfk_2')]
class Candidature
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'statut', type: 'string', length: 255, nullable: false)]
    private string $statut;
    #[Assert\NotNull]
    #[ORM\Column(name: 'lettreMotivation', type: 'string', length: 255, nullable: false)]
    private string $lettremotivation;

    #[ORM\Column(name: 'noteTest', type: 'string', length: 10, nullable: false)]
    private string $notetest;

    #[Assert\Date]
    #[ORM\Column(name: 'dateAjout', type: 'string', length: 250, nullable: false)]
    private string $dateajout;
    #[Assert\NotNull]
    #[ORM\Column(name: 'Cv', type: 'string', length: 255, nullable: false)]
    private string $cv;
    #[Assert\NotNull]
    #[ORM\Column(name: 'NiveauFrancais', type: 'string', length: 255, nullable: false)]
    private string $niveaufrancais;
    #[Assert\NotNull]
    #[ORM\Column(name: 'NiveauAnglais', type: 'string', length: 255, nullable: false)]
    private string $niveauanglais;
    #[Assert\NotNull]
    #[ORM\Column(name: 'diplome', type: 'string', length: 255, nullable: false)]
    private string $diplome;
    #[ORM\Column(name: 'dateExpiration', type: 'string', length: 255, nullable: false)]
    private string $dateexpiration;
    #[ORM\Column(name: 'titre', type: 'string', length: 255, nullable: false)]
    private string $titre;
    #[Assert\NotNull]
    #[ORM\Column(name: 'TypeCondidature', type: 'string', length: 255, nullable: false)]
    private string $typecondidature;
    #[Assert\NotNull]
    #[ORM\Column(name: 'Experience', type: 'string', length: 25, nullable: false)]
    private string $experience;
    #[Assert\NotNull]
    #[ORM\Column(name: 'Domaine', type: 'string', length: 255, nullable: false)]
    private string $domaine;

    #[ORM\JoinColumn(name: 'id_offre', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Offre')]
    private \App\Entity\Offre $idOffre;
    #[ORM\JoinColumn(name: 'idcondidat', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Utilisateur')]
    private \App\Entity\Utilisateur $idcondidat;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Offre
     */
    public function getIdOffre(): Offre
    {
        return $this->idOffre;
    }

    /**
     * @param Offre $idOffre
     */
    public function setIdOffre(Offre $idOffre): void
    {
        $this->idOffre = $idOffre;
    }

    /**
     * @return Utilisateur
     */
    public function getIdcondidat(): Utilisateur
    {
        return $this->idcondidat;
    }

    /**
     * @param Utilisateur $idcondidat
     */
    public function setIdcondidat(Utilisateur $idcondidat): void
    {
        $this->idcondidat = $idcondidat;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getLettremotivation(): ?string
    {
        return $this->lettremotivation;
    }

    public function setLettremotivation(string $lettremotivation): self
    {
        $this->lettremotivation = $lettremotivation;

        return $this;
    }

    public function getNotetest(): ?string
    {
        return $this->notetest;
    }

    public function setNotetest(string $notetest): self
    {
        $this->notetest = $notetest;

        return $this;
    }

    public function getDateajout(): ?string
    {
        return $this->dateajout;
    }

    public function setDateajout(string $dateajout): self
    {
        $this->dateajout = $dateajout;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getNiveaufrancais(): ?string
    {
        return $this->niveaufrancais;
    }

    public function setNiveaufrancais(string $niveaufrancais): self
    {
        $this->niveaufrancais = $niveaufrancais;

        return $this;
    }

    public function getNiveauanglais(): ?string
    {
        return $this->niveauanglais;
    }

    public function setNiveauanglais(string $niveauanglais): self
    {
        $this->niveauanglais = $niveauanglais;

        return $this;
    }

    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(string $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getDateexpiration(): ?string
    {
        return $this->dateexpiration;
    }

    public function setDateexpiration(string $dateexpiration): self
    {
        $this->dateexpiration = $dateexpiration;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getTypecondidature(): ?string
    {
        return $this->typecondidature;
    }

    public function setTypecondidature(string $typecondidature): self
    {
        $this->typecondidature = $typecondidature;

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(string $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(string $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }


}
