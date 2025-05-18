<?php

namespace App\Entity;

use App\Repository\NutrientTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NutrientTypeRepository::class)]
class NutrientType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recipe:read'])]
    private string $name;

    #[ORM\Column(length: 50)]
    #[Groups(['recipe:read'])]
    private string $unit;

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
    public function getUnit(): string
    {
        return $this->unit;
    }
    public function setUnit(string $unit): self
    {
        $this->unit = $unit;
        return $this;
    }
}
