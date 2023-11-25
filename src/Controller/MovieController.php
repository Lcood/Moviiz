<?php

namespace App\Controller;

use App\Repository\MoviesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/movie', name: 'app_movie')]
    public function index(MoviesRepository $moviesRepository): Response
    {
        #permet de récupérer les derniers films uploader
        $movies = $moviesRepository->findBy([],['id' => 'DESC']);
        dump($movies);
        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
        ]);
    }
}
