<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class RecipeNutrient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'nutrients')]
    private ?Recipe $recipe = null;

    #[ORM\Column]
    #[Groups(['recipe:read'])]
    private float $amount;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['recipe:read'])]
    private string $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }
}
