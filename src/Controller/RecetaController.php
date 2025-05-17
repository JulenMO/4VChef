<?php

namespace App\Controller;

use App\Entity\Receta;
use App\Entity\Ingrediente;
use App\Entity\Paso;
use App\Entity\TipoNutriente;
use App\Entity\RecetaNutriente;
use App\Repository\RecetaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/recetas')]
class RecetaController extends AbstractController
{
    #[Route('', name: 'get_recetas', methods: ['GET'])]
    public function getRecetas(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $search = $request->query->get('search');
        $comensales = $request->query->get('comensales');
        $ingrediente = $request->query->get('ingrediente');
        $nutriente = $request->query->get('nutriente');
        $min = $request->query->get('min');

        $qb = $em->createQueryBuilder()
            ->select('r')
            ->from(Receta::class, 'r');

        if ($search) {
            $qb->andWhere('LOWER(r.titulo) LIKE :search')
                ->setParameter('search', '%' . strtolower($search) . '%');
        }

        if ($comensales) {
            $qb->andWhere('r.numComensales >= :comensales')
                ->setParameter('comensales', (int)$comensales);
        }

        if ($ingrediente) {
            $qb->join('r.ingredientes', 'i')
                ->andWhere('LOWER(i.nombre) LIKE :ingrediente')
                ->setParameter('ingrediente', '%' . strtolower($ingrediente) . '%');
        }

        if ($nutriente) {
            $qb->join('r.recetaNutrientes', 'rn')
                ->join('rn.tipoNutriente', 'tn')
                ->andWhere('LOWER(tn.nombre) LIKE :nutriente')
                ->setParameter('nutriente', '%' . strtolower($nutriente) . '%');

            if ($min !== null) {
                $qb->andWhere('rn.cantidad >= :min')
                    ->setParameter('min', (float)$min);
            }
        }

        $recetas = $qb->getQuery()->getResult();
        $data = [];

        foreach ($recetas as $receta) {
            $data[] = [
                'id' => $receta->getId(),
                'titulo' => $receta->getTitulo(),
                'numComensales' => $receta->getNumComensales(),
                'createdAt' => $receta->getCreatedAt()->format('Y-m-d H:i:s')
            ];
        }

        return $this->json($data);
    }

    #[Route('', name: 'post_receta', methods: ['POST'])]
    public function postReceta(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['titulo'], $data['numComensales'])) {
            return $this->json(['error' => 'Faltan campos obligatorios: titulo o numComensales'], 400);
        }

        $receta = new Receta();
        $receta->setTitulo($data['titulo']);
        $receta->setNumComensales($data['numComensales']);
        $receta->setCreatedAt(new \DateTimeImmutable());

        $em->persist($receta);
        $em->flush();

        return $this->json([
            'id' => $receta->getId(),
            'titulo' => $receta->getTitulo(),
            'numComensales' => $receta->getNumComensales(),
            'createdAt' => $receta->getCreatedAt()->format('Y-m-d H:i:s')
        ], 201);
    }

    #[Route('/completa', name: 'post_receta_completa', methods: ['POST'])]
    public function postRecetaCompleta(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validaciones
        if (
            empty($data['titulo']) ||
            empty($data['numComensales']) ||
            empty($data['ingredientes']) || !is_array($data['ingredientes']) || count($data['ingredientes']) < 1 ||
            empty($data['pasos']) || !is_array($data['pasos']) || count($data['pasos']) < 1 ||
            empty($data['nutrientes']) || !is_array($data['nutrientes']) || count($data['nutrientes']) < 1
        ) {
            return $this->json([
                'error' => 'Debes enviar título, número de comensales, y al menos 1 ingrediente, 1 paso y 1 nutriente válido.'
            ], 400);
        }

        // Crear receta base
        $receta = new Receta();
        $receta->setTitulo($data['titulo']);
        $receta->setNumComensales($data['numComensales']);
        $receta->setCreatedAt(new \DateTimeImmutable());

        // INGREDIENTES
        foreach ($data['ingredientes'] as $ingData) {
            if (!isset($ingData['nombre'], $ingData['cantidad'], $ingData['unidad'])) continue;

            $ingrediente = new Ingrediente();
            $ingrediente->setNombre($ingData['nombre']);
            $ingrediente->setCantidad($ingData['cantidad']);
            $ingrediente->setUnidad($ingData['unidad']);
            $ingrediente->setReceta($receta);

            $em->persist($ingrediente);
        }

        // PASOS
        foreach ($data['pasos'] as $pasoData) {
            if (!isset($pasoData['descripcion'], $pasoData['orden'])) continue;

            $paso = new Paso();
            $paso->setDescripcion($pasoData['descripcion']);
            $paso->setOrden($pasoData['orden']);
            $paso->setReceta($receta);

            $em->persist($paso);
        }

        // NUTRIENTES
        foreach ($data['nutrientes'] as $nutData) {
            if (!isset($nutData['tipoNutrienteId'], $nutData['cantidad'])) continue;

            $tipo = $em->getRepository(TipoNutriente::class)->find($nutData['tipoNutrienteId']);
            if (!$tipo) continue;

            $rn = new RecetaNutriente();
            $rn->setTipoNutriente($tipo);
            $rn->setCantidad($nutData['cantidad']);
            $rn->setUnidad($tipo->getUnidad());
            $rn->setReceta($receta);

            $em->persist($rn);
        }

        // Guardar todo
        $em->persist($receta);
        $em->flush();

        return $this->json([
            'id' => $receta->getId(),
            'mensaje' => 'Receta creada con ingredientes, pasos y nutrientes'
        ], 201);
    }
    #[Route('/{id}', name: 'get_receta_completa', methods: ['GET'])]
    public function getRecetaCompleta(int $id, EntityManagerInterface $em): JsonResponse
    {
        $receta = $em->getRepository(Receta::class)->find($id);

        if (!$receta) {
            return $this->json(['error' => 'Receta no encontrada'], 404);
        }

        // Ingredientes
        $ingredientes = [];
        foreach ($receta->getIngredientes() as $ing) {
            $ingredientes[] = [
                'nombre' => $ing->getNombre(),
                'cantidad' => $ing->getCantidad(),
                'unidad' => $ing->getUnidad()
            ];
        }

        // Pasos
        $pasos = [];
        foreach ($receta->getPasos() as $paso) {
            $pasos[] = [
                'descripcion' => $paso->getDescripcion(),
                'orden' => $paso->getOrden()
            ];
        }

        // Nutrientes
        $nutrientes = [];
        foreach ($receta->getRecetaNutrientes() as $rn) {
            $nutrientes[] = [
                'nombre' => $rn->getTipoNutriente()->getNombre(),
                'unidad' => $rn->getUnidad(),
                'cantidad' => $rn->getCantidad()
            ];
        }

        // Votos (media)
        $votos = $receta->getVotos();
        $media = null;
        if (count($votos) > 0) {
            $total = 0;
            foreach ($votos as $v) {
                $total += $v->getValor();
            }
            $media = round($total / count($votos), 1);
        }

        return $this->json([
            'id' => $receta->getId(),
            'titulo' => $receta->getTitulo(),
            'numComensales' => $receta->getNumComensales(),
            'createdAt' => $receta->getCreatedAt()->format('Y-m-d H:i:s'),
            'ingredientes' => $ingredientes,
            'pasos' => $pasos,
            'nutrientes' => $nutrientes,
            'mediaVotos' => $media
        ]);
    }
    #[Route('/{id}', name: 'put_receta', methods: ['PUT'])]
    public function updateReceta(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $receta = $em->getRepository(Receta::class)->find($id);

        if (!$receta) {
            return $this->json(['error' => 'Receta no encontrada'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['titulo'])) {
            $receta->setTitulo($data['titulo']);
        }

        if (isset($data['numComensales'])) {
            $receta->setNumComensales($data['numComensales']);
        }

        $em->flush();

        return $this->json([
            'mensaje' => 'Receta actualizada correctamente',
            'id' => $receta->getId(),
            'titulo' => $receta->getTitulo(),
            'numComensales' => $receta->getNumComensales()
        ]);
    }
    #[Route('/{id}', name: 'delete_receta', methods: ['DELETE'])]
    public function deleteReceta(int $id, EntityManagerInterface $em): JsonResponse
    {
        $receta = $em->getRepository(Receta::class)->find($id);

        if (!$receta) {
            return $this->json(['error' => 'Receta no encontrada'], 404);
        }

        $em->remove($receta);
        $em->flush();

        return $this->json(['mensaje' => 'Receta eliminada correctamente']);
    }
}
