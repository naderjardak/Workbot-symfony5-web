<?php

namespace App\Entity;

use App\Repository\AdsLikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdsLikeRepository::class)]
class AdsLike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    private ?Ads $ads = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    private ?Utilisateur $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAds(): ?Ads
    {
        return $this->ads;
    }

    public function setAds(?Ads $ads): self
    {
        $this->ads = $ads;

        return $this;
    }
    public function getUser(): ?Utilisateur
    {
        return $this->user;
    }

    public function setUser(?Utilisateur $user): self
    {
        $this->user = $user;

        return $this;
    }
}
