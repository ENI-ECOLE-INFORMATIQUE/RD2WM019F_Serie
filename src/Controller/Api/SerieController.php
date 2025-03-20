<?php

namespace App\Controller\Api;

use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SerieController extends AbstractController
{
    public function __construct(private SerieRepository $serieRepository)
    {
    }

    #[Route('/api/serie', name: 'api_serie_all', methods: ['GET'])]
    public function all(): Response
    {
        $series = $this->serieRepository->findAll();
        return $this->json($series, Response::HTTP_OK, [], ['groups' => 'get_serie']);
    }

    #[Route('/api/serie/{id}', name: 'api_serie_one', methods: ['GET'])]
    public function one(int $id): Response
    {
        $serie = $this->serieRepository->find($id);
        return $this->json($serie, Response::HTTP_OK, [], ['groups' => 'get_serie']);
    }

    #[Route('/api/serie', name: 'api_serie_create', methods: ['POST'])]
    public function create(): Response
    {

    }

    #[Route('/api/serie/{id}', name: 'api_serie_update', methods: ['PUT', 'PATCH'])]
    public function update(): Response
    {

    }

    #[Route('/api/serie/{id}', name: 'api_serie_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $serie = $this->serieRepository->find($id);
        $entityManager->remove($serie);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}





