<?php

namespace App\Entity;

use App\Repository\DeckRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Color;

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
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GameFormat")
     */
    private $primary_format;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="decks")
     */
    private $primary_player;


    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="Color")
     * @ORM\JoinTable(name="decks_colors",
     *      joinColumns={@ORM\JoinColumn(name="deck_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="color_id", referencedColumnName="id")}
     *      )
     */
    private $colors;


    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="Commander", inversedBy="decks")
     * @ORM\JoinTable(name="commanders_decks")
     */
    private $commanders;

    /**
     * @ORM\OneToMany(targetEntity=GamePlayer::class, mappedBy="Deck")
     */
    private $deckGames;

    public function __construct()
    {
        $this->colors = new ArrayCollection();
        $this->commanders = new ArrayCollection();
        $this->deckGames = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrimaryPlayer(): ?Player
    {
        return $this->primary_player;
    }

    public function setPrimaryPlayer(?Player $primary_player): self
    {
        $this->primary_player = $primary_player;

        return $this;
    }

    /**
     * @return Collection|Color[]
     */
    public function getColors(): Collection
    {
        return $this->colors;
    }

    public function addColor(Color $color): self
    {
        if (!$this->colors->contains($color)) {
            $this->colors[] = $color;
        }

        return $this;
    }

    public function removeColor(Color $color): self
    {
        $this->colors->removeElement($color);

        return $this;
    }

    /**
     * @return Collection|Commander[]
     */
    public function getCommanders(): Collection
    {
        return $this->commanders;
    }

    public function addCommander(Commander $commander): self
    {
        if (!$this->commanders->contains($commander)) {
            $this->commanders[] = $commander;
        }

        return $this;
    }

    public function removeCommander(Commander $commander): self
    {
        $this->commanders->removeElement($commander);

        return $this;
    }

    public function getPrimaryFormat(): ?GameFormat
    {
        return $this->primary_format;
    }

    public function setPrimaryFormat(?GameFormat $primary_format): self
    {
        $this->primary_format = $primary_format;

        return $this;
    }

    /**
     * @return Collection|GamePlayer[]
     */
    public function getDeckGames(): Collection
    {
        return $this->deckGames;
    }

    public function addDeckGame(GamePlayer $deckGame): self
    {
        if (!$this->deckGames->contains($deckGame)) {
            $this->deckGames[] = $deckGame;
            $deckGame->setDeck($this);
        }

        return $this;
    }

    public function removeDeckGame(GamePlayer $deckGame): self
    {
        if ($this->deckGames->removeElement($deckGame)) {
            // set the owning side to null (unless already changed)
            if ($deckGame->getDeck() === $this) {
                $deckGame->setDeck(null);
            }
        }

        return $this;
    }

    public function __toString(){
      return $this->getName();
    }
}
