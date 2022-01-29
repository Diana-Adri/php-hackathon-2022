<?php

namespace App\Entity;

use App\Repository\ProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProgramRepository::class)
 */
class Program
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_participants;

    /**
     * @ORM\Column(type="integer")
     */
    private $room;

    /**
     * @ORM\ManyToOne(targetEntity=Interval::class, inversedBy="programs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $TimeInterval;

    /**
     * @ORM\OneToMany(targetEntity=Bookings::class, mappedBy="Program", orphanRemoval=true)
     */
    private $bookings;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sport;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaxParticipants(): ?int
    {
        return $this->max_participants;
    }

    public function setMaxParticipants(int $max_participants): self
    {
        $this->max_participants = $max_participants;

        return $this;
    }

    public function getRoom(): ?int
    {
        return $this->room;
    }

    public function setRoom(int $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getTimeInterval(): ?Interval
    {
        return $this->TimeInterval;
    }

    public function setTimeInterval(?Interval $TimeInterval): self
    {
        $this->TimeInterval = $TimeInterval;

        return $this;
    }

    /**
     * @return Collection|Bookings[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Bookings $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setProgram($this);
        }

        return $this;
    }

    public function removeBooking(Bookings $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getProgram() === $this) {
                $booking->setProgram(null);
            }
        }

        return $this;
    }

    public function getSport(): ?string
    {
        return $this->sport;
    }

    public function setSport(string $sport): self
    {
        $this->sport = $sport;

        return $this;
    }

}
