<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class Player
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
     * @ORM\Column(type="string", length=128)
     */
    private $Nickname;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Deck", mappedBy="primary_player")
     */
    private $decks;

    /**
     * @ORM\OneToMany(targetEntity=GamePlayer::class, mappedBy="Player", orphanRemoval=true)
     */
    private $playerGames;


    public function __construct()
    {
        $this->decks = new ArrayCollection();
        $this->playerGames = new ArrayCollection();
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

    public function getNickname(): ?string
    {
        return $this->Nickname;
    }

    public function setNickname(string $Nickname): self
    {
        $this->Nickname = $Nickname;

        return $this;
    }

    /**
     * @return ArrayCollection|Deck[]
     */
    public function getDecks(): ArrayCollection
    {
        return $this->decks;
    }

    public function __toString(){
      return $this->getName();
    }

    /**
     * @return Collection|GamePlayer[]
     */
    public function getPlayerGames(): Collection
    {
        return $this->playerGames;
    }

    public function addPlayerGame(GamePlayer $playerGame): self
    {
        if (!$this->playerGames->contains($playerGame)) {
            $this->playerGames[] = $playerGame;
            $playerGame->setPlayer($this);
        }

        return $this;
    }

    public function removePlayerGame(GamePlayer $playerGame): self
    {
        if ($this->playerGames->removeElement($playerGame)) {
            // set the owning side to null (unless already changed)
            if ($playerGame->getPlayer() === $this) {
                $playerGame->setPlayer(null);
            }
        }

        return $this;
    }



}
