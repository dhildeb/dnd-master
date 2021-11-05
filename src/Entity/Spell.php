<?php

namespace App\Entity;

use App\Repository\SpellRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpellRepository::class)
 */
class Spell
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $higher_level;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $spell_range;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $components = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $material;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ritual;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $duration;

    /**
     * @ORM\Column(type="boolean")
     */
    private $concentration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $casting_time;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $attack_type;

    /**
     * @ORM\Column(type="object")
     */
    private $damage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $school;

    /**
     * @ORM\Column(type="array")
     */
    private $classes = [];

    /**
     * @ORM\Column(type="array")
     */
    private $subclasses = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getHigherLevel(): ?string
    {
        return $this->higher_level;
    }

    public function setHigherLevel(?string $higher_level): self
    {
        $this->higher_level = $higher_level;

        return $this;
    }

    public function getSpellRange(): ?string
    {
        return $this->spell_range;
    }

    public function setSpellRange(?string $spell_range): self
    {
        $this->spell_range = $spell_range;

        return $this;
    }

    public function getComponents(): ?array
    {
        return $this->components;
    }

    public function setComponents(?array $components): self
    {
        $this->components = $components;

        return $this;
    }

    public function getMaterial(): ?string
    {
        return $this->material;
    }

    public function setMaterial(?string $material): self
    {
        $this->material = $material;

        return $this;
    }

    public function getRitual(): ?bool
    {
        return $this->ritual;
    }

    public function setRitual(bool $ritual): self
    {
        $this->ritual = $ritual;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getConcentration(): ?bool
    {
        return $this->concentration;
    }

    public function setConcentration(bool $concentration): self
    {
        $this->concentration = $concentration;

        return $this;
    }

    public function getCastingTime(): ?string
    {
        return $this->casting_time;
    }

    public function setCastingTime(string $casting_time): self
    {
        $this->casting_time = $casting_time;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getAttackType(): ?string
    {
        return $this->attack_type;
    }

    public function setAttackType(string $attack_type): self
    {
        $this->attack_type = $attack_type;

        return $this;
    }

    public function getDamage()
    {
        return $this->damage;
    }

    public function setDamage($damage): self
    {
        $this->damage = $damage;

        return $this;
    }

    public function getSchool(): ?string
    {
        return $this->school;
    }

    public function setSchool(string $school): self
    {
        $this->school = $school;

        return $this;
    }

    public function getClasses(): ?array
    {
        return $this->classes;
    }

    public function setClasses(array $classes): self
    {
        $this->classes = $classes;

        return $this;
    }

    public function getSubclasses(): ?array
    {
        return $this->subclasses;
    }

    public function setSubclasses(array $subclasses): self
    {
        $this->subclasses = $subclasses;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
