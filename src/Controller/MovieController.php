<?php

namespace App\Controller;

use App\Entity\Movies;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\MoviesRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function show(Movies $movie, Request $request, EntityManagerInterface $entityManagerInterface, Security $security, 
                         ReviewRepository $reviewRepository, SessionInterface $session): Response
    {
        # stock le préceédent URL 
        $session->set('previous_url', $request->getUri());

        #filtre pour récupérer la note moyenne depuis le ReviewRepository.php
        $averageRate = $reviewRepository->findOneBy($movie->getId());

        $user= $security->getUser();


        $genreId = $request->get('genreId');
        # rechercher si l'utilisateur a deja posté un review : si oui lui donner la possibilité de modifier son post 
        $review = $reviewRepository->findMovies($genreId);
       
        # si l'utilisateur n'a jamais fait de reviews
        if(!$review) {  
            $review = new Review();
            $review->setMovie($movie);
            $review->setUser($user);
            $review->setApproved(false);

        } else {
            
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $entityManagerInterface->persist($review);
            $entityManagerInterface->flush();
        }

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'form' => $form,
            'user' => $user,
            'averageRate' => $averageRate,
        ]);
    }
    }
}
