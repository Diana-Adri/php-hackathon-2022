<?php

namespace App\Entity;

use App\Repository\BookingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookingsRepository::class)
 */
class Bookings
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
    private $user_cnp;

    /**
     * @ORM\ManyToOne(targetEntity=Program::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Program;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserCnp(): ?int
    {
        return $this->user_cnp;
    }

    public function setUserCnp(int $user_cnp): self
    {
        $this->user_cnp = $user_cnp;

        return $this;
    }

    public function getProgram(): ?Program
    {
        return $this->Program;
    }

    public function setProgram(?Program $Program): self
    {
        $this->Program = $Program;

        return $this;
    }
}
