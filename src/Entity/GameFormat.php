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
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberPlayers;

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

    public function getNumberPlayers(): ?int
    {
        return $this->numberPlayers;
    }

    public function setNumberPlayers(int $numberPlayers): self
    {
        $this->numberPlayers = $numberPlayers;

        return $this;
    }

    public function __toString(){
      return $this->getName();
    }
}
