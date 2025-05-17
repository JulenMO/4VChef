<?php

namespace App\Controller;

use App\Repository\TipoNutrienteRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/nutrientes')]
class NutrienteController extends AbstractController
{
    #[Route('', name: 'get_nutrientes', methods: ['GET'])]
    public function getNutrientes(TipoNutrienteRepository $repo): JsonResponse
    {
        $nutrientes = $repo->findAll();
        $data = [];

        foreach ($nutrientes as $n) {
            $data[] = [
                'id' => $n->getId(),
                'nombre' => $n->getNombre(),
                'unidad' => $n->getUnidad()
            ];
        }

        return $this->json($data);
    }
}
