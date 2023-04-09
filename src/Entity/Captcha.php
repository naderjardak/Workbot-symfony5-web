<?php

namespace App\Entity;

use App\Repository\CaptchaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Captcha
 *
 */
#[ORM\Entity(repositoryClass: CaptchaRepository::class)]
#[ORM\Table(name: 'captcha')]
class Captcha
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'status', type: 'integer', nullable: false)]
    private int $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }


}
