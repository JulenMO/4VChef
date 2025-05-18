<?php

namespace App\Entity;

use App\Repository\StepRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StepRepository::class)]
class Step
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['recipe:read'])]
    private int $order;

    #[ORM\Column(length: 255)]
    #[Groups(['recipe:read'])]
    private string $description;

    #[ORM\ManyToOne(inversedBy: 'steps')]
    private ?Recipe $recipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getOrder(): int
    {
        return $this->order;
    }
    public function setOrder(int $order): self
    {
        $this->order = $order;
        return $this;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): self
    {
        $this->description = $description;
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
