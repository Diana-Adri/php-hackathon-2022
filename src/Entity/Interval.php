<?php

namespace App\Entity;

use App\Repository\IntervalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IntervalRepository::class)
 * @ORM\Table(name="`interval`")
 */
class Interval
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_datetime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stop_datetime;


    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDatetime(): ?\DateTimeInterface
    {
        return $this->start_datetime;
    }

    public function setStartDatetime(\DateTimeInterface $start_datetime): self
    {
        $this->start_datetime = $start_datetime;

        return $this;
    }

    public function getStopDatetime(): ?\DateTimeInterface
    {
        return $this->stop_datetime;
    }

    public function setStopDatetime(\DateTimeInterface $stop_datetime): self
    {
        $this->stop_datetime = $stop_datetime;

        return $this;
    }

}
