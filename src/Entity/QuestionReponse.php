<?php

namespace App\Entity;

use App\Entity\Quiz;
use App\Repository\QuestionReponseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * QuestionReponse
 *
 */
#[ORM\Entity(repositoryClass: QuestionReponseRepository::class)]
#[ORM\Table(name: 'question_reponse')]
#[ORM\Index(columns: ['id_quiz'], name: 'id_quiz')]
class QuestionReponse
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'question', type: 'string', length: 255, nullable: false)]
    private string $question;

    #[ORM\Column(name: 'reponse_f1', type: 'string', length: 255, nullable: false)]
    private string $reponseF1;

    #[ORM\Column(name: 'reponse_f2', type: 'string', length: 255, nullable: false)]
    private string $reponseF2;

    #[ORM\Column(name: 'reponse_v', type: 'string', length: 255, nullable: false)]
    private string $reponseV;

    #[ORM\JoinColumn(name: 'id_quiz', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Quiz')]
    private Quiz $idQuiz;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getReponseF1(): ?string
    {
        return $this->reponseF1;
    }

    public function setReponseF1(string $reponseF1): self
    {
        $this->reponseF1 = $reponseF1;

        return $this;
    }

    public function getReponseF2(): ?string
    {
        return $this->reponseF2;
    }

    public function setReponseF2(string $reponseF2): self
    {
        $this->reponseF2 = $reponseF2;

        return $this;
    }

    public function getReponseV(): ?string
    {
        return $this->reponseV;
    }

    public function setReponseV(string $reponseV): self
    {
        $this->reponseV = $reponseV;

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

#test






}
