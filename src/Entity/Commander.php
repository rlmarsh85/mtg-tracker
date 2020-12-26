<?php

namespace App\Entity;

use App\Repository\CommanderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Deck;

/**
 * @ORM\Entity(repositoryClass=CommanderRepository::class)
 */
class Commander
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
     * @ORM\ManyToMany(targetEntity=Color::class)
     */
    private $Colors;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Scryfall_URL;

    /**
     * Many Commanders have many Decks
     * @ORM\ManyToMany(targetEntity="Deck", mappedBy="commanders")
     */
     private $decks;

    public function __construct()
    {
        $this->Colors = new ArrayCollection();
        $this->decks = new ArrayCollection();
    }

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

    /**
     * @return Collection|Color[]
     */
    public function getColors(): Collection
    {
        return $this->Colors;
    }

    public function addColor(Color $color): self
    {
        if (!$this->Colors->contains($color)) {
            $this->Colors[] = $color;
        }

        return $this;
    }

    public function removeColor(Color $color): self
    {
        $this->Colors->removeElement($color);

        return $this;
    }

    public function getScryfallURL(): ?string
    {
        return $this->Scryfall_URL;
    }

    public function setScryfallURL(?string $Scryfall_URL): self
    {
        $this->Scryfall_URL = $Scryfall_URL;

        return $this;
    }

    /**
     * @return Collection|Decks[]
     */
    public function getDecks(): Collection
    {
        return $this->decks;
    }

    public function addDeck(Decks $deck): self
    {
        if (!$this->decks->contains($deck)) {
            $this->decks[] = $deck;
            $deck->addCommander($this);
        }

        return $this;
    }

    public function removeDeck(Decks $deck): self
    {
        if ($this->decks->removeElement($deck)) {
            $deck->removeCommander($this);
        }

        return $this;
    }
}
