<?php

namespace App\Controller\Api;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function create(
        Request                $request,
        SerializerInterface    $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface     $validator
    ): Response
    {
        $data = $request->getContent();
        $serie = $serializer->deserialize($data, Serie::class, 'json');

        //vérifier les données
        $errors = $validator->validate($serie);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($serie);
        $entityManager->flush();

        return $this->json(
            $serie,
            Response::HTTP_CREATED,
            //en création, on renvoie en générale l'URI de la ressource créée
            [
                "Location" => $this->generateUrl(
                    'api_serie_one',
                    ['id' => $serie->getId()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ],
            ['groups' => 'get_serie']
        );

    }

    #[Route('/api/serie/{id}', name: 'api_serie_update', methods: ['PUT', 'PATCH'])]
    public function update(
        Serie                  $serie,
        EntityManagerInterface $entityManager,
        Request                $request
    ): Response
    {
        $data = $request->getContent();
        $data = json_decode($data);

        $serie->setNbLike($serie->getNbLike() + $data->nbLike);
        $entityManager->persist($serie);
        $entityManager->flush();

        return $this->json(["nbLike" => $serie->getNbLike()], Response::HTTP_OK);
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





