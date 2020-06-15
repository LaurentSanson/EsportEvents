<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TournamentRepository::class)
 */
class Tournament
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxRegistration;

    /**
     * @ORM\OneToOne(targetEntity=Event::class, mappedBy="tournament", cascade={"persist", "remove"})
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity=TournamentType::class, inversedBy="tournaments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournamentType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaxRegistration(): ?int
    {
        return $this->maxRegistration;
    }

    public function setMaxRegistration(int $maxRegistration): self
    {
        $this->maxRegistration = $maxRegistration;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        // set (or unset) the owning side of the relation if necessary
        $newTournament = null === $event ? null : $this;
        if ($event->getTournament() !== $newTournament) {
            $event->setTournament($newTournament);
        }

        return $this;
    }

    public function getTournamentType(): ?TournamentType
    {
        return $this->tournamentType;
    }

    public function setTournamentType(?TournamentType $tournamentType): self
    {
        $this->tournamentType = $tournamentType;

        return $this;
    }
}
