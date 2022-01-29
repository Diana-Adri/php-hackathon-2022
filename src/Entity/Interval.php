<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\IntervalRepository;
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
     * @var string
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
    public function getStartDate(): string
    {
        return $this->start_date;
    }

    /**
     * @param string $start_date
     * @return Interval
     */
    public function setStartDate(string $start_date): Interval
    {
        $this->start_date = $start_date;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndDate(): string
    {
        return $this->end_date;
    }

    /**
     * @param string $end_date
     * @return Interval
     */
    public function setEndDate(string $end_date): Interval
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