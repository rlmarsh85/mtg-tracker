<?php

namespace App\Entity;

use App\Repository\GameFormatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameFormatRepository::class)
 */
class GameFormat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=24)
     */
    private $Name;

    /**
     * @ORM\Column(type="integer")
     */
    private $NumberPlayers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getNumberPlayers(): ?int
    {
        return $this->NumberPlayers;
    }

    public function setNumberPlayers(int $NumberPlayers): self
    {
        $this->NumberPlayers = $NumberPlayers;

        return $this;
    }
}
