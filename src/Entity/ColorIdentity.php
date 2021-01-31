<?php

namespace App\Entity;

use App\Repository\ColorIdentityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ColorIdentityRepository::class)
 */
class ColorIdentity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ColorCombo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ComboName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColorCombo(): ?string
    {
        return $this->ColorCombo;
    }

    public function setColorCombo(string $ColorCombo): self
    {
        $this->ColorCombo = $ColorCombo;

        return $this;
    }

    public function getComboName(): ?string
    {
        return $this->ComboName;
    }

    public function setComboName(string $ComboName): self
    {
        $this->ComboName = $ComboName;

        return $this;
    }
}
