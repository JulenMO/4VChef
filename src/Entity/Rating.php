<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $value;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    private ?Recipe $recipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getValue(): int
    {
        return $this->value;
    }
    public function setValue(int $value): self
    {
        $this->value = $value;
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
