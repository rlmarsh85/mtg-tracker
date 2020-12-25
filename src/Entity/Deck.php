<?php

namespace App\Entity;

use App\Repository\DeckRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeckRepository::class)
 */
class Deck
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $Name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $format_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $primary_player_id;

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

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getFormatId(): ?int
    {
        return $this->format_id;
    }

    public function setFormatId(?int $format_id): self
    {
        $this->format_id = $format_id;

        return $this;
    }

    public function getPrimaryPlayerId(): ?int
    {
        return $this->primary_player_id;
    }

    public function setPrimaryPlayerId(int $primary_player_id): self
    {
        $this->primary_player_id = $primary_player_id;

        return $this;
    }
}
