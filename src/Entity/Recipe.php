<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

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
    #[SerializedName('title')]
    private string $title;

    #[ORM\Column]
    #[Groups(['recipe:read'])]
    #[SerializedName('number-diner')]
    private int $numberDiner;

    #[ORM\OneToMany(mappedBy: "recipe", targetEntity: Ingredient::class, cascade: ['persist'], orphanRemoval: true)]
    #[Groups(['recipe:read'])]
    private Collection $ingredients;

    #[ORM\OneToMany(mappedBy: "recipe", targetEntity: Step::class, cascade: ['persist'], orphanRemoval: true)]
    #[Groups(['recipe:read'])]
    private Collection $steps;

    #[ORM\OneToMany(mappedBy: "recipe", targetEntity: RecipeNutrient::class, cascade: ['persist'], orphanRemoval: true)]
    #[Groups(['recipe:read'])]
    private Collection $nutrients;

    #[ORM\OneToMany(mappedBy: "recipe", targetEntity: Rating::class, orphanRemoval: true)]
    private Collection $ratings;

    #[ORM\Column(type: "datetime_immutable")]
    #[Groups(['recipe:read'])]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->nutrients = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
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
    public function getSteps(): Collection
    {
        return $this->steps;
    }
    public function getNutrients(): Collection
    {
        return $this->nutrients;
    }
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[Groups(['recipe:read'])]
    #[SerializedName('rating')]
    public function getRating(): array
    {
        $total = count($this->ratings);
        $avg = $total > 0 ? array_sum(array_map(fn($r) => $r->getValue(), $this->ratings->toArray())) / $total : 0;
        return [
            'number-votes' => $total,
            'rating-avg' => round($avg, 2)
        ];
    }
}
