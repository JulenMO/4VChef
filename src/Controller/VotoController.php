<?php

namespace App\Controller;

use App\Entity\Receta;
use App\Entity\Voto;
use App\Repository\RecetaRepository;
use App\Repository\VotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/votos')]
class VotoController extends AbstractController
{
    #[Route('', name: 'post_voto', methods: ['POST'])]
    public function votar(
        Request $request,
        EntityManagerInterface $em,
        RecetaRepository $recetaRepo,
        VotoRepository $votoRepo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['receta_id'], $data['valor'])) {
            return $this->json(['error' => 'Faltan campos: receta_id o valor'], 400);
        }

        $valor = (int)$data['valor'];
        if ($valor < 0 || $valor > 5) {
            return $this->json(['error' => 'El voto debe estar entre 0 y 5'], 400);
        }

        $receta = $recetaRepo->find($data['receta_id']);
        if (!$receta) {
            return $this->json(['error' => 'Receta no encontrada'], 404);
        }

        $ip = $request->getClientIp();

        // Verificar si esta IP ya votó esta receta
        $votoExistente = $votoRepo->findOneBy(['ip' => $ip, 'receta' => $receta]);
        if ($votoExistente) {
            return $this->json(['error' => 'Ya has votado esta receta'], 403);
        }

        $voto = new Voto();
        $voto->setValor($valor);
        $voto->setIp($ip);
        $voto->setReceta($receta);

        $em->persist($voto);
        $em->flush();

        return $this->json([
            'id' => $voto->getId(),
            'valor' => $voto->getValor(),
            'ip' => $voto->getIp(),
            'receta_id' => $receta->getId()
        ], 201);
    }
}
