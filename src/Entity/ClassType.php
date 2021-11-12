<?php

namespace App\Entity;

use App\Repository\ClassTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClassTypeRepository::class)
 */
class ClassType
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
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $hitDie;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $proficiencies = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $savingThrows = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $startingEquipment = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getHitDie(): ?int
    {
        return $this->hitDie;
    }

    public function setHitDie(int $hitDie): self
    {
        $this->hitDie = $hitDie;

        return $this;
    }

    public function getProficiencies(): ?array
    {
        return $this->proficiencies;
    }

    public function setProficiencies(?array $proficiencies): self
    {
        $this->proficiencies = $proficiencies;

        return $this;
    }

    public function getSavingThrows(): ?array
    {
        return $this->savingThrows;
    }

    public function setSavingThrows(?array $savingThrows): self
    {
        $this->savingThrows = $savingThrows;

        return $this;
    }

    public function getStartingEquipment(): ?array
    {
        return $this->startingEquipment;
    }

    public function setStartingEquipment(?array $startingEquipment): self
    {
        $this->startingEquipment = $startingEquipment;

        return $this;
    }
}
