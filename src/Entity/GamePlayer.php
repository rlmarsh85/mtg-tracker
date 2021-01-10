<?php

namespace App\Entity;

use App\Repository\GamePlayerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GamePlayerRepository::class)
 */
class GamePlayer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="gamePlayers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Game;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class, inversedBy="playerGames")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Player;

    /**
     * @ORM\ManyToOne(targetEntity=Deck::class, inversedBy="deckGames")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Deck;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $WinningPlayer;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $FirstOrSecondTurnSolRing;    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->Game;
    }

    public function setGame(?Game $Game): self
    {
        $this->Game = $Game;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->Player;
    }

    public function setPlayer(?Player $Player): self
    {
        $this->Player = $Player;

        return $this;
    }

    public function getDeck(): ?Deck
    {
        return $this->Deck;
    }

    public function setDeck(?Deck $Deck): self
    {
        $this->Deck = $Deck;

        return $this;
    }

    public function getWinningPlayer(): ?bool
    {
        return $this->WinningPlayer;
    }

    public function setWinningPlayer(?bool $WinningPlayer): self
    {
        $this->WinningPlayer = $WinningPlayer;

        return $this;
    }

    public function getFirstOrSecondTurnSolRing(): ?bool
    {
        return $this->FirstOrSecondTurnSolRing;
    }

    public function setFirstOrSecondTurnSolRing(?bool $FirstOrSecondTurnSolRing): self
    {
        $this->FirstOrSecondTurnSolRing = $FirstOrSecondTurnSolRing;

        return $this;
    }
}
