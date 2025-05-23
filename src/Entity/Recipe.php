<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recipe:read'])]
    private string $title;

    #[ORM\Column]
    #[Groups(['recipe:read'])]
    private int $numberDiner;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Ingredient::class, cascade: ['persist'], orphanRemoval: true)]
    #[Groups(['recipe:read'])]
    private Collection $ingredients;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Step::class, cascade: ['persist'], orphanRemoval: true)]
    #[Groups(['recipe:read'])]
    private Collection $steps;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeNutrient::class, cascade: ['persist'], orphanRemoval: true)]
    #[Groups(['recipe:read'])]
    private Collection $nutrients;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Rating::class, cascade: ['persist'], orphanRemoval: true)]
    #[Groups(['recipe:read'])]
    private Collection $ratings;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->nutrients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getNumberDiner(): int
    {
        return $this->numberDiner;
    }

    public function setNumberDiner(int $numberDiner): self
    {
        $this->numberDiner = $numberDiner;
        return $this;
    }

    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setRecipe($this);
        }
        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            if ($ingredient->getRecipe() === $this) {
                $ingredient->setRecipe(null);
            }
        }
        return $this;
    }

    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setRecipe($this);
        }
        return $this;
    }

    public function removeStep(Step $step): self
    {
        if ($this->steps->removeElement($step)) {
            if ($step->getRecipe() === $this) {
                $step->setRecipe(null);
            }
        }
        return $this;
    }

    public function getNutrients(): Collection
    {
        return $this->nutrients;
    }

    public function addNutrient(RecipeNutrient $nutrient): self
    {
        if (!$this->nutrients->contains($nutrient)) {
            $this->nutrients[] = $nutrient;
            $nutrient->setRecipe($this);
        }
        return $this;
    }

    public function removeNutrient(RecipeNutrient $nutrient): self
    {
        if ($this->nutrients->removeElement($nutrient)) {
            if ($nutrient->getRecipe() === $this) {
                $nutrient->setRecipe(null);
            }
        }
        return $this;
    }

    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setRecipe($this);
        }
        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            if ($rating->getRecipe() === $this) {
                $rating->setRecipe(null);
            }
        }
        return $this;
    }
}
