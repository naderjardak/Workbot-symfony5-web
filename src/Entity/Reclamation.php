<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Reclamation
 *
 */
#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
#[ORM\Table(name: 'reclamation')]

#[ORM\Index(columns: ['id_categorie'], name: 'fk_categorie')]
#[ORM\Index(columns: ['id_utilisateur'], name: 'fk_utilisateur')]
#[ORM\Index(columns: ['id_offre'], name: 'fk_offre')]
class Reclamation
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[Assert\NotBlank]
    #[ORM\Column(name: 'objet', type: 'string', length: 255, nullable: true)]
    private ?string $objet = null;

    #[ORM\Column(name: 'dateAjout', type: 'date', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTime $dateajout;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 10,
        max: 256,
        minMessage: 'Description must be at least {{ limit }} characters long',
        maxMessage: 'Description cannot be longer than {{ limit }} characters',
    )]
    #[ORM\Column(name: 'description', type: 'string', length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'image', type: 'string', length: 300, nullable: true)]
    private $image;

    #[ORM\Column(name: 'etat', type: 'string',length: 255, nullable: false, options: ['default' => 'envoyée'])]
    private ?string $etat = 'envoyée';

    #[ORM\JoinColumn(name: 'id_categorie', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Categorie')]
    private \App\Entity\Categorie $idCategorie;

    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Utilisateur')]
    private \App\Entity\Utilisateur $idUtilisateur;

    #[ORM\JoinColumn(name: 'id_offre', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Offre')]
    private \App\Entity\Offre $idOffre;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(?string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getDateajout(): ?\DateTime
    {
        return $this->dateajout;
    }

    public function setDateajout(?\DateTime $dateajout): self
    {
        $this->dateajout = $dateajout;

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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }


    /**
     * @return Categorie
     */
    public function getIdCategorie(): Categorie
    {
        return $this->idCategorie;
    }

    /**
     * @param Categorie $idCategorie
     */
    public function setIdCategorie(Categorie $idCategorie): void
    {
        $this->idCategorie = $idCategorie;
    }

    /**
     * @return Utilisateur
     */
    public function getIdUtilisateur(): Utilisateur
    {
        return $this->idUtilisateur;
    }

    /**
     * @param Utilisateur $idUtilisateur
     */
    public function setIdUtilisateur(Utilisateur $idUtilisateur): void
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
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

}
