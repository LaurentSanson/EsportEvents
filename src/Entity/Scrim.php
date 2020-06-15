<?php

namespace App\Entity;

use App\Repository\ScrimRepository;
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
     * @ORM\OneToOne(targetEntity=Event::class, mappedBy="scrim", cascade={"persist", "remove"})
     */
    private $event;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        // set (or unset) the owning side of the relation if necessary
        $newScrim = null === $event ? null : $this;
        if ($event->getScrim() !== $newScrim) {
            $event->setScrim($newScrim);
        }

        return $this;
    }
}
