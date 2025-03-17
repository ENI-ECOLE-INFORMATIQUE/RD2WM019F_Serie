<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SeasonController extends AbstractController
{
    #[Route('/season/add/{serieId}', name: 'season_add')]
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        SerieRepository $serieRepository,
        int $serieId = null
    ): Response
    {
        $season = new Season();
        if($serieId){
            $serie = $serieRepository->find($serieId);
            $season->setSerie($serie);
            $season->setNumber($serie->getSeasons()->count() + 1);
        }
        $seasonForm = $this->createForm(SeasonType::class, $season);

        $seasonForm->handleRequest($request);

        if($seasonForm->isSubmitted() && $seasonForm->isValid()){

            $season->setDateCreated(new \DateTime());
            $entityManager->persist($season);
            $entityManager->flush();

            $this->addFlash('success', 'Season created !');
            return $this->redirectToRoute('series_detail', ['id' => $season->getSerie()->getId()]);
        }

        return $this->render('season/add.html.twig', [
            'seasonForm' => $seasonForm
        ]);
    }
}
