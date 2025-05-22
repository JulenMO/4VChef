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
    #[Groups(['recipe:read'])]
    private ?Recipe $recipe = null;

    #[ORM\ManyToOne]
    #[Groups(['recipe:read'])]
    private ?NutrientType $nutrientType = null;

    #[ORM\Column]
    #[Groups(['recipe:read'])]
    private float $amount;

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

    public function getNutrientType(): ?NutrientType
    {
        return $this->nutrientType;
    }

    public function setNutrientType(?NutrientType $nutrientType): self
    {
        $this->nutrientType = $nutrientType;
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
