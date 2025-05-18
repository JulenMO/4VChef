<?php

namespace App\Entity;

use App\Repository\RecipeNutrientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RecipeNutrientRepository::class)]
class RecipeNutrient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['recipe:read'])]
    private float $quantity;

    #[ORM\ManyToOne(inversedBy: 'nutrients')]
    private ?Recipe $recipe = null;

    #[ORM\ManyToOne]
    #[Groups(['recipe:read'])]
    private ?NutrientType $nutrientType = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getQuantity(): float
    {
        return $this->quantity;
    }
    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
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
}
