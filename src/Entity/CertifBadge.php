<?php

namespace App\Entity;

use App\Repository\CertifBadgeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * CertifBadge
 *
 */
#[ORM\Entity(repositoryClass: CertifBadgeRepository::class)]
#[ORM\Table(name: 'certif_badge')]
#[ORM\Index(columns: ['id_user'], name: 'fk_user')]
#[ORM\Index(columns: ['id_badge'], name: 'fb_badge')]
#[ORM\Index(columns: ['id_certif'], name: 'fk_certif')]
class CertifBadge
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\JoinColumn(name: 'id_certif', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Certification')]
    private Certification $idCertif;

    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Utilisateur')]
    private Utilisateur $idUser;

    #[ORM\JoinColumn(name: 'id_badge', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Badge')]
    private Badge $idBadge;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Certification
     */
    public function getIdCertif(): Certification
    {
        return $this->idCertif;
    }

    /**
     * @param Certification $idCertif
     */
    public function setIdCertif(Certification $idCertif): void
    {
        $this->idCertif = $idCertif;
    }

    /**
     * @return Utilisateur
     */
    public function getIdUser(): Utilisateur
    {
        return $this->idUser;
    }

    /**
     * @param Utilisateur $idUser
     */
    public function setIdUser(Utilisateur $idUser): void
    {
        $this->idUser = $idUser;
    }

    /**
     * @return Badge
     */
    public function getIdBadge(): Badge
    {
        return $this->idBadge;
    }

    /**
     * @param Badge $idBadge
     */
    public function setIdBadge(Badge $idBadge): void
    {
        $this->idBadge = $idBadge;
    }



}
