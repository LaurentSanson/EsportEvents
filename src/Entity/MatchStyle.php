<?php

namespace App\Entity;

use App\Repository\MatchStyleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatchStyleRepository::class)
 */
class MatchStyle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Scrim::class, mappedBy="matchStyle")
     */
    private $scrims;

    /**
     * @ORM\ManyToMany(targetEntity=Tournament::class, mappedBy="matchStyle")
     */
    private $tournaments;

    public function __construct()
    {
        $this->scrims = new ArrayCollection();
        $this->tournaments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Scrim[]
     */
    public function getScrims(): Collection
    {
        return $this->scrims;
    }

    public function addScrim(Scrim $scrim): self
    {
        if (!$this->scrims->contains($scrim)) {
            $this->scrims[] = $scrim;
            $scrim->setMatchStyle($this);
        }

        return $this;
    }

    public function removeScrim(Scrim $scrim): self
    {
        if ($this->scrims->contains($scrim)) {
            $this->scrims->removeElement($scrim);
            // set the owning side to null (unless already changed)
            if ($scrim->getMatchStyle() === $this) {
                $scrim->setMatchStyle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tournament[]
     */
    public function getTournaments(): Collection
    {
        return $this->tournaments;
    }

    public function addTournament(Tournament $tournament): self
    {
        if (!$this->tournaments->contains($tournament)) {
            $this->tournaments[] = $tournament;
            $tournament->addMatchStyle($this);
        }

        return $this;
    }

    public function removeTournament(Tournament $tournament): self
    {
        if ($this->tournaments->contains($tournament)) {
            $this->tournaments->removeElement($tournament);
            $tournament->removeMatchStyle($this);
        }

        return $this;
    }
}
