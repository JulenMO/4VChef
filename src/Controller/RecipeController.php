<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Entity\Step;
use App\Entity\Rating;
use App\Entity\RecipeNutrient;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/recipes')]
class RecipeController extends AbstractController
{
    #[Route('', name: 'get_recipes', methods: ['GET'])]
    public function getAll(RecipeRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll(), 200, [], ['groups' => 'recipe:read']);
    }

    #[Route('', name: 'post_recipe', methods: ['POST'])]
    public function create(Request $req, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($req->getContent(), true);

        $recipe = new Recipe();
        $recipe->setTitle($data['title'] ?? '');
        $recipe->setNumberDiner($data['number-diner'] ?? 1);

        foreach ($data['ingredients'] ?? [] as $ingData) {
            $ingredient = new Ingredient();
            $ingredient->setName($ingData['name']);
            $ingredient->setQuantity($ingData['quantity']);
            $ingredient->setUnit($ingData['unit']);
            $ingredient->setRecipe($recipe);
            $recipe->addIngredient($ingredient);
        }

        foreach ($data['steps'] ?? [] as $stepData) {
            $step = new Step();
            $step->setStepOrder($stepData['stepOrder']);
            $step->setDescription($stepData['description']);
            $step->setRecipe($recipe);
            $recipe->addStep($step);
        }

        foreach ($data['nutrients'] ?? [] as $nutriData) {
            $recipeNutrient = new RecipeNutrient();
            $recipeNutrient->setName($nutriData['name']);
            $recipeNutrient->setAmount($nutriData['amount']);
            $recipeNutrient->setRecipe($recipe);
            $recipe->addNutrient($recipeNutrient);
        }

        $em->persist($recipe);
        $em->flush();

        return $this->json($recipe, 201, [], ['groups' => 'recipe:read']);
    }

    #[Route('/{id}/rating/{rate}', name: 'rate_recipe', methods: ['POST'])]
    public function rate(int $id, int $rate, RecipeRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        if ($rate < 0 || $rate > 5) {
            return $this->json(['code' => 21, 'description' => 'The rate must be 0–5'], 400);
        }

        $recipe = $repo->find($id);
        if (!$recipe) {
            return $this->json(['code' => 22, 'description' => 'Recipe not found'], 400);
        }

        $rating = new Rating();
        $rating->setRecipe($recipe);
        $rating->setValue($rate);
        $em->persist($rating);
        $em->flush();

        return $this->json($recipe, 200, [], ['groups' => 'recipe:read']);
    }
}
