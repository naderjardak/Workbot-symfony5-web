<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Cours
 *
 */
#[ORM\Entity(repositoryClass: CoursRepository::class)]
#[ORM\Table(name: 'cours')]
class Cours
{

    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[Assert\NotBlank(message: "Champ Obligatoire !!")]
    #[Assert\Length(min:2,max: 20,minMessage:'ecrire un titre valide !',maxMessage: 'le titre est de longeure >20 !!')]
    #[ORM\Column(name: 'titre', type: 'string', length: 100, nullable: false)]
    private string $titre;

    #[Assert\NotBlank(message: "Champ Obligatoire !!")]
    #[ORM\Column(name: 'matiere', type: 'string', length: 100, nullable: false)]
    private string $matiere;

    #[Assert\NotBlank(message: "Champ Obligatoire !!")]
    #[ORM\Column(name: 'domaine', type: 'string', length: 200, nullable: false)]
    private string $domaine;

    #[Assert\NotBlank(message: "Champ Obligatoire !!")]
    #[ORM\Column(name: 'categorie', type: 'string', length: 30, nullable: false)]
    private string $categorie;

    #[Assert\Text]
    #[ORM\Column(name: 'chemin', type: 'string', length: 200, nullable: false)]
    private string $chemin;

    #[Assert\Text]
    #[ORM\Column(name: 'logo', type: 'string', length: 200, nullable: false)]
    private string $logo;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): self
    {
        $this->matiere = $matiere;

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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getChemin(): ?string
    {
        return $this->chemin;
    }

    public function setChemin(string $chemin): self
    {
        $this->chemin = $chemin;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

}
