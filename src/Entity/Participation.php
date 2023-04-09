<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 */
#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
#[ORM\Table(name: 'participation')]
#[ORM\Index(columns: ['id_event'], name: 'id_event')]
#[ORM\Index(columns: ['id_userP'], name: 'id_userP')]
class Participation
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\JoinColumn(name: 'id_event', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Evennement')]
    private Evennement $idEvent;

    #[ORM\JoinColumn(name: 'id_userP', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Utilisateur')]
    private Utilisateur $idUserp;

    /**
     * @return Evennement
     */
    public function getIdEvent(): Evennement
    {
        return $this->idEvent;
    }

    /**
     * @param Evennement $idEvent
     */
    public function setIdEvent(Evennement $idEvent): void
    {
        $this->idEvent = $idEvent;
    }

    /**
     * @return Utilisateur
     */
    public function getIdUserp(): Utilisateur
    {
        return $this->idUserp;
    }

    /**
     * @param Utilisateur $idUserp
     */
    public function setIdUserp(Utilisateur $idUserp): void
    {
        $this->idUserp = $idUserp;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


}
