<?php

namespace App\Entity;

use App\Repository\RecetaNutrienteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetaNutrienteRepository::class)]
class RecetaNutriente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $cantidad = null;

    #[ORM\Column(length: 255)]
    private ?string $unidad = null;

    #[ORM\ManyToOne(inversedBy: 'recetaNutrientes')]
    private ?Receta $receta = null;

    #[ORM\ManyToOne(inversedBy: 'recetaNutrientes')]
    private ?TipoNutriente $tipoNutriente = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCantidad(): ?float
    {
        return $this->cantidad;
    }

    public function setCantidad(float $cantidad): static
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getUnidad(): ?string
    {
        return $this->unidad;
    }

    public function setUnidad(string $unidad): static
    {
        $this->unidad = $unidad;

        return $this;
    }

    public function getReceta(): ?Receta
    {
        return $this->receta;
    }

    public function setReceta(?Receta $receta): static
    {
        $this->receta = $receta;

        return $this;
    }

    public function getTipoNutriente(): ?TipoNutriente
    {
        return $this->tipoNutriente;
    }

    public function setTipoNutriente(?TipoNutriente $tipoNutriente): static
    {
        $this->tipoNutriente = $tipoNutriente;

        return $this;
    }
}
