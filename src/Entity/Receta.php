<?php

namespace App\Entity;

use App\Repository\RecetaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetaRepository::class)]
class Receta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column]
    private ?int $numComensales = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'receta', targetEntity: Ingrediente::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $ingredientes;

    #[ORM\OneToMany(mappedBy: 'receta', targetEntity: Paso::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $pasos;

    #[ORM\OneToMany(mappedBy: 'receta', targetEntity: RecetaNutriente::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $recetaNutrientes;

    #[ORM\OneToMany(mappedBy: 'receta', targetEntity: Voto::class, orphanRemoval: true)]
    private Collection $votos;

    public function __construct()
    {
        $this->ingredientes = new ArrayCollection();
        $this->pasos = new ArrayCollection();
        $this->recetaNutrientes = new ArrayCollection();
        $this->votos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;
        return $this;
    }

    public function getNumComensales(): ?int
    {
        return $this->numComensales;
    }

    public function setNumComensales(int $numComensales): self
    {
        $this->numComensales = $numComensales;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return Collection<int, Ingrediente>
     */
    public function getIngredientes(): Collection
    {
        return $this->ingredientes;
    }

    public function addIngrediente(Ingrediente $ingrediente): self
    {
        if (!$this->ingredientes->contains($ingrediente)) {
            $this->ingredientes[] = $ingrediente;
            $ingrediente->setReceta($this);
        }

        return $this;
    }

    public function removeIngrediente(Ingrediente $ingrediente): self
    {
        if ($this->ingredientes->removeElement($ingrediente)) {
            if ($ingrediente->getReceta() === $this) {
                $ingrediente->setReceta(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Paso>
     */
    public function getPasos(): Collection
    {
        return $this->pasos;
    }

    public function addPaso(Paso $paso): self
    {
        if (!$this->pasos->contains($paso)) {
            $this->pasos[] = $paso;
            $paso->setReceta($this);
        }

        return $this;
    }

    public function removePaso(Paso $paso): self
    {
        if ($this->pasos->removeElement($paso)) {
            if ($paso->getReceta() === $this) {
                $paso->setReceta(null);
            }
        }

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
            $rn->setReceta($this);
        }

        return $this;
    }

    public function removeRecetaNutriente(RecetaNutriente $rn): self
    {
        if ($this->recetaNutrientes->removeElement($rn)) {
            if ($rn->getReceta() === $this) {
                $rn->setReceta(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Voto>
     */
    public function getVotos(): Collection
    {
        return $this->votos;
    }

    public function addVoto(Voto $voto): self
    {
        if (!$this->votos->contains($voto)) {
            $this->votos[] = $voto;
            $voto->setReceta($this);
        }

        return $this;
    }

    public function removeVoto(Voto $voto): self
    {
        if ($this->votos->removeElement($voto)) {
            if ($voto->getReceta() === $this) {
                $voto->setReceta(null);
            }
        }

        return $this;
    }
}
