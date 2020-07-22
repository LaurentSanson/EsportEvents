<?php

namespace App\Entity;

use App\Repository\PlatformRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlatformRepository::class)
 */
class Platform
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
     * @ORM\ManyToMany(targetEntity=Game::class, mappedBy="platforms")
     */
    private $games;

    /**
     * @ORM\OneToMany(targetEntity=Scrim::class, mappedBy="platform")
     */
    private $scrims;

    /**
     * @ORM\OneToMany(targetEntity=Tournament::class, mappedBy="platform")
     */
    private $tournaments;

    public function __construct()
    {
        $this->games = new ArrayCollection();
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
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->addPlatform($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            $game->removePlatform($this);
        }

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
            $scrim->setPlatform($this);
        }

        return $this;
    }

    public function removeScrim(Scrim $scrim): self
    {
        if ($this->scrims->contains($scrim)) {
            $this->scrims->removeElement($scrim);
            // set the owning side to null (unless already changed)
            if ($scrim->getPlatform() === $this) {
                $scrim->setPlatform(null);
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
            $tournament->setPlatform($this);
        }

        return $this;
    }

    public function removeTournament(Tournament $tournament): self
    {
        if ($this->tournaments->contains($tournament)) {
            $this->tournaments->removeElement($tournament);
            // set the owning side to null (unless already changed)
            if ($tournament->getPlatform() === $this) {
                $tournament->setPlatform(null);
            }
        }

        return $this;
    }
}
