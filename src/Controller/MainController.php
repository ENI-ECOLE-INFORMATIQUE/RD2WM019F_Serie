<?php

namespace App\Controller;

use App\Models\Lorem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MainController extends AbstractController
{
//    /**
//     * @Route('/main', name = {'app_main'})
//     */
    #[Route('/home', name: 'main_home')]
    #[Route('/')]
    public function home(): Response
    {
        $username = "<h2>Sylvain</h2>";
        $serie = ["name" => "Emily in Paris", "year" => 2020];

        return $this->render('main/home.html.twig', [
            "serie" => $serie,
            "name" => $username
        ]);
    }

    #[Route('/test', name: 'main_test')]
    public function test(
        SerializerInterface $serializer
    ): Response
    {
        //envoie de donnée
//        $ch = curl_init("https://jsonplaceholder.typicode.com/todos/");
//        curl_setopt($ch, CURLOPT_URL, "https://www.analyse-innovation-solution-fr/404");
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_exec($ch);

        //récupération
        $content = file_get_contents("https://jsonplaceholder.typicode.com/todos/");
        //dd(json_decode($content));
        $lorems = $serializer->deserialize($content, Lorem::class.'[]', 'json');

        dd($lorems);


        return $this->render('main/test.html.twig', [
            'lorems' => $lorems
        ]);
    }
}
