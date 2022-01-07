<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(FilmRepository $filmRepository): Response
    {

        $films=$filmRepository->findAll();

        return $this->render('home/index.html.twig', [
            "films"=>$films,
            'controller_name' => 'HomeController',
        ]);
    }
}
