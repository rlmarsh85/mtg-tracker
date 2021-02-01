<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $PlayDate;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $NumberTurns;    

    /**
     * @ORM\ManyToOne(targetEntity=GameFormat::class,nullable=false)
     */
    private $Format;

    /**
     * @ORM\OneToMany(targetEntity=GamePlayer::class, mappedBy="Game", orphanRemoval=true, cascade={"persist"})
     */
    private $gamePlayers;


    public function __construct()
    {
        $this->gamePlayers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayDate(): ?\DateTimeInterface
    {
        return $this->PlayDate;
    }

    public function setPlayDate(?\DateTimeInterface $PlayDate): self
    {
        $this->PlayDate = $PlayDate;

        return $this;
    }

    public function getFormat(): ?GameFormat
    {
        return $this->Format;
    }

    public function setFormat(?GameFormat $Format): self
    {
        $this->Format = $Format;

        return $this;
    }

    /**
     * @return Collection|GamePlayer[]
     */
    public function getGamePlayers(): Collection
    {
        return $this->gamePlayers;
    }

    public function addGamePlayer(GamePlayer $gamePlayer): self
    {
        if (!$this->gamePlayers->contains($gamePlayer)) {
            $this->gamePlayers[] = $gamePlayer;
            $gamePlayer->setGame($this);
        }

        return $this;
    }

    public function removeGamePlayer(GamePlayer $gamePlayer): self
    {
        if ($this->gamePlayers->removeElement($gamePlayer)) {
            // set the owning side to null (unless already changed)
            if ($gamePlayer->getGame() === $this) {
                $gamePlayer->setGame(null);
            }
        }

        return $this;
    }

    public function getNumberTurns()
    {
        return $this->NumberTurns;
    }

    public function setNumberTurns($NumberTurns): self
    {
        $this->NumberTurns = $NumberTurns;

        return $this;
    }


}
