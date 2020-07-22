<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
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
     * @ORM\ManyToMany(targetEntity=Platform::class, inversedBy="games")
     */
    private $platforms;

    /**
     * @ORM\OneToMany(targetEntity=Tournament::class, mappedBy="game")
     */
    private $tournaments;

    /**
     * @ORM\OneToMany(targetEntity=Scrim::class, mappedBy="game")
     */
    private $scrims;

    public function __construct()
    {
        $this->platforms = new ArrayCollection();
        $this->tournaments = new ArrayCollection();
        $this->scrims = new ArrayCollection();
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
     * @return Collection|Platform[]
     */
    public function getPlatforms(): Collection
    {
        return $this->platforms;
    }

    public function addPlatform(Platform $platform): self
    {
        if (!$this->platforms->contains($platform)) {
            $this->platforms[] = $platform;
        }

        return $this;
    }

    public function removePlatform(Platform $platform): self
    {
        if ($this->platforms->contains($platform)) {
            $this->platforms->removeElement($platform);
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
            $tournament->setGame($this);
        }

        return $this;
    }

    public function removeTournament(Tournament $tournament): self
    {
        if ($this->tournaments->contains($tournament)) {
            $this->tournaments->removeElement($tournament);
            // set the owning side to null (unless already changed)
            if ($tournament->getGame() === $this) {
                $tournament->setGame(null);
            }
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
            $scrim->setGame($this);
        }

        return $this;
    }

    public function removeScrim(Scrim $scrim): self
    {
        if ($this->scrims->contains($scrim)) {
            $this->scrims->removeElement($scrim);
            // set the owning side to null (unless already changed)
            if ($scrim->getGame() === $this) {
                $scrim->setGame(null);
            }
        }

        return $this;
    }
}
