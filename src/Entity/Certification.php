<?php

namespace App\Entity;

use App\Entity\Quiz;
use App\Repository\CertificationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Certification
 *
 */
#[ORM\Entity(repositoryClass: CertificationRepository::class)]
#[ORM\Table(name: 'certification')]
#[ORM\Index(columns: ['id_quiz'], name: 'id_quiz')]
class Certification
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[Assert\NotBlank(message: "Champ Obligatoire !!")]
    #[ORM\Column(name: 'titreCours', type: 'string', length: 100, nullable: false)]
    private string $titrecours;

    #[Assert\NotBlank(message: "Champ Obligatoire !!")]
    #[ORM\Column(name: 'titreTest', type: 'string', length: 100, nullable: false)]
    private string $titretest;

    #[ORM\Column(name: 'dateAjout', type: 'string', length: 50, nullable: false)]
    private string $dateajout;

    #[Assert\Image]
    #[ORM\Column(name: 'lien', type: 'string', length: 200, nullable: false)]
    private string $lien;

    #[ORM\JoinColumn(name: 'id_quiz', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Quiz')]
    private Quiz $idQuiz;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitrecours(): ?string
    {
        return $this->titrecours;
    }

    public function setTitrecours(string $titrecours): self
    {
        $this->titrecours = $titrecours;

        return $this;
    }

    public function getTitretest(): ?string
    {
        return $this->titretest;
    }

    public function setTitretest(string $titretest): self
    {
        $this->titretest = $titretest;

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

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    /**
     * @return \App\Entity\Quiz
     */
    public function getIdQuiz(): \App\Entity\Quiz
    {
        return $this->idQuiz;
    }

    /**
     * @param \App\Entity\Quiz $idQuiz
     */
    public function setIdQuiz(\App\Entity\Quiz $idQuiz): void
    {
        $this->idQuiz = $idQuiz;
    }


}
