<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\ManyToOne(targetEntity=TournamentType::class, inversedBy="tournaments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournamentType;

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
    private $tournamentDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tournamentLimitRegistrationDate;

    /**
     * @ORM\ManyToMany(targetEntity=Player::class, inversedBy="tournaments")
     */
    private $players;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="tournaments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity=Platform::class, inversedBy="tournaments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $platform;

    public function __construct()
    {
        $this->players = new ArrayCollection();
    }

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

    public function getTournamentType(): ?TournamentType
    {
        return $this->tournamentType;
    }

    public function setTournamentType(?TournamentType $tournamentType): self
    {
        $this->tournamentType = $tournamentType;

        return $this;
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

    public function getTournamentDate(): ?\DateTimeInterface
    {
        return $this->tournamentDate;
    }

    public function setTournamentDate(\DateTimeInterface $tournamentDate): self
    {
        $this->tournamentDate = $tournamentDate;

        return $this;
    }

    public function getTournamentLimitRegistrationDate(): ?\DateTimeInterface
    {
        return $this->tournamentLimitRegistrationDate;
    }

    public function setTournamentLimitRegistrationDate(\DateTimeInterface $tournamentLimitRegistrationDate): self
    {
        $this->tournamentLimitRegistrationDate = $tournamentLimitRegistrationDate;

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
}
