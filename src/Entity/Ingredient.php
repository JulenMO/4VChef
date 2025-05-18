<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recipe:read'])]
    private string $name;

    #[ORM\Column]
    #[Groups(['recipe:read'])]
    private float $quantity;

    #[ORM\Column(length: 50)]
    #[Groups(['recipe:read'])]
    private string $unit;

    #[ORM\ManyToOne(inversedBy: 'ingredients')]
    private ?Recipe $recipe = null;

    public function getId(): ?int
    {
        return $this->id;
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
    public function getQuantity(): float
    {
        return $this->quantity;
    }
    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }
    public function getUnit(): string
    {
        return $this->unit;
    }
    public function setUnit(string $unit): self
    {
        $this->unit = $unit;
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
}
