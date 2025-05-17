<?php

namespace App\Entity;

use App\Repository\TipoNutrienteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TipoNutrienteRepository::class)]
class TipoNutriente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 50)]
    private ?string $unidad = null;

    #[ORM\OneToMany(mappedBy: 'tipoNutriente', targetEntity: RecetaNutriente::class)]
    private Collection $recetaNutrientes;

    public function __construct()
    {
        $this->recetaNutrientes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getUnidad(): ?string
    {
        return $this->unidad;
    }

    public function setUnidad(string $unidad): self
    {
        $this->unidad = $unidad;
        return $this;
    }

    /**
     * @return Collection<int, RecetaNutriente>
     */
    public function getRecetaNutrientes(): Collection
    {
        return $this->recetaNutrientes;
    }

    public function addRecetaNutriente(RecetaNutriente $rn): self
    {
        if (!$this->recetaNutrientes->contains($rn)) {
            $this->recetaNutrientes[] = $rn;
            $rn->setTipoNutriente($this);
        }

        return $this;
    }

    public function removeRecetaNutriente(RecetaNutriente $rn): self
    {
        if ($this->recetaNutrientes->removeElement($rn)) {
            if ($rn->getTipoNutriente() === $this) {
                $rn->setTipoNutriente(null);
            }
        }

        return $this;
    }
}
