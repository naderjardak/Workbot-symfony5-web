<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $nsc = null;

    /**
     * @return int|null
     */
    public function getNsc(): ?int
    {
        return $this->nsc;
    }

    /**
     * @param int|null $nsc
     */
    public function setNsc(?int $nsc): void
    {
        $this->nsc = $nsc;
    }

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    private ?classroom $classroom = null;


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getClassroom(): ?classroom
    {
        return $this->classroom;
    }

    public function setClassroom(?classroom $classroom): self
    {
        $this->classroom = $classroom;

        return $this;
    }
}
