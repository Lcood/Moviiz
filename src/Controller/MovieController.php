<?php

namespace App\Controller;

use App\Entity\Movies;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\MoviesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MovieController extends AbstractController
{
    #[Route('/films', name: 'app_films')]
    public function index(MoviesRepository $moviesRepository): Response
    {
        #permet de récupérer les derniers films uploader
        $movies = $moviesRepository->findBy([],['id' => 'DESC']);
        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
        ]);
    }
    #[Route('/films{id}', name: 'movie_show')]
    public function show(Movies $movie): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }
}
