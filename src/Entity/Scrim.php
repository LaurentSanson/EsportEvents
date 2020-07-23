<?php

namespace App\Entity;

use App\Repository\ScrimRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScrimRepository::class)
 */
class Scrim
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $scrimDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $scrimlimitRegistrationDate;

    /**
     * @ORM\ManyToMany(targetEntity=Player::class, inversedBy="scrims")
     */
    private $players;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="scrims", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity=Platform::class, inversedBy="scrims")
     * @ORM\JoinColumn(nullable=false)
     */
    private $platform;

    /**
     * @ORM\ManyToMany(targetEntity=Team::class, mappedBy="scrims")
     */
    private $teams;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbMaxTeams;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->teams = new ArrayCollection();
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getScrimDate(): ?\DateTimeInterface
    {
        return $this->scrimDate;
    }

    public function setScrimDate(\DateTimeInterface $scrimDate): self
    {
        $this->scrimDate = $scrimDate;

        return $this;
    }

    public function getScrimlimitRegistrationDate(): ?\DateTimeInterface
    {
        return $this->scrimlimitRegistrationDate;
    }

    public function setScrimlimitRegistrationDate(\DateTimeInterface $scrimlimitRegistrationDate): self
    {
        $this->scrimlimitRegistrationDate = $scrimlimitRegistrationDate;

        return $this;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
        }

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getPlatform(): ?Platform
    {
        return $this->platform;
    }

    public function setPlatform(?Platform $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->addScrim($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
            $team->removeScrim($this);
        }

        return $this;
    }

    public function getNbMaxTeams(): ?int
    {
        return $this->nbMaxTeams;
    }

    public function setNbMaxTeams(int $nbMaxTeams): self
    {
        $this->nbMaxTeams = $nbMaxTeams;

        return $this;
    }

}
