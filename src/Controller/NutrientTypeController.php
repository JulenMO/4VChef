<?php

namespace App\Controller;

use App\Repository\NutrientTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class NutrientTypeController extends AbstractController
{
    #[Route('/nutrient-types', name: 'get_nutrient_types', methods: ['GET'])]
    public function getAll(NutrientTypeRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }
}
