<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
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
    private $eventDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $limitRegistrationDate;

    /**
     * @ORM\ManyToMany(targetEntity=Player::class, mappedBy="events")
     */
    private $players;

    /**
     * @ORM\OneToOne(targetEntity=Tournament::class, inversedBy="event", cascade={"persist", "remove"})
     */
    private $tournament;

    /**
     * @ORM\OneToOne(targetEntity=Scrim::class, inversedBy="event", cascade={"persist", "remove"})
     */
    private $scrim;

    /**
     * @ORM\ManyToMany(targetEntity=Platform::class, inversedBy="events")
     */
    private $platform;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->platform = new ArrayCollection();
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

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(\DateTimeInterface $eventDate): self
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    public function getLimitRegistrationDate(): ?\DateTimeInterface
    {
        return $this->limitRegistrationDate;
    }

    public function setLimitRegistrationDate(\DateTimeInterface $limitRegistrationDate): self
    {
        $this->limitRegistrationDate = $limitRegistrationDate;

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
            $player->addEvent($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            $player->removeEvent($this);
        }

        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getScrim(): ?Scrim
    {
        return $this->scrim;
    }

    public function setScrim(?Scrim $scrim): self
    {
        $this->scrim = $scrim;

        return $this;
    }

    /**
     * @return Collection|Platform[]
     */
    public function getPlatform(): Collection
    {
        return $this->platform;
    }

    public function addPlatform(Platform $platform): self
    {
        if (!$this->platform->contains($platform)) {
            $this->platform[] = $platform;
        }

        return $this;
    }

    public function removePlatform(Platform $platform): self
    {
        if ($this->platform->contains($platform)) {
            $this->platform->removeElement($platform);
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
}
