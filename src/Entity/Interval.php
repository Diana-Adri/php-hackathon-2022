<?php

namespace App\Entity;

use App\Repository\IntervalRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="interval")
 * @ORM\Entity(repositoryClass=IntervalRepository::class)
 */
class Interval
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $start_date;

    /**
     * @var string
     *
     * @ORM\Column(name="end_date", type="datetime")
     */
    private $end_date;

    /**
     * @ORM\OneToMany(targetEntity=Program::class, mappedBy="TimeInterval", orphanRemoval=true)
     */
    private $programs;

    public function __construct()
    {
        $this->programs = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    public function setStartDate(DateTimeInterface $start_date)
    {
        $this->start_date = $start_date;
        return $this;
    }

    public function getEndDate()
    {
        return $this->end_date;
    }

    public function setEndDate(DateTimeInterface $end_date)
    {
        $this->end_date = $end_date;
        return $this;
    }

    /**
     * @return Collection|Program[]
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Program $program): self
    {
        if (!$this->programs->contains($program)) {
            $this->programs[] = $program;
            $program->setTimeInterval($this);
        }

        return $this;
    }

    public function removeProgram(Program $program): self
    {
        if ($this->programs->removeElement($program)) {
            // set the owning side to null (unless already changed)
            if ($program->getTimeInterval() === $this) {
                $program->setTimeInterval(null);
            }
        }

        return $this;
    }

}