<?php

namespace App\Controller;

use App\Repository\HackathonRepository;
use App\Repository\RessourcesRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function indexx(RessourcesRepository $ressourcesRepository): Response
    {
        $ressources = $ressourcesRepository->findAll();

        return $this->render('home/index.html.twig', [
            'ressources' =>  $ressources ,
        ]);
    }
    #[Route('/home', name: 'app_home')]
    public function index(HackathonRepository $hackathonRepository): Response
    {
        $hackathons = $hackathonRepository->findAll();
    
        return $this->render('home/index.html.twig', [
            'hackathons' => $hackathons,
        ]);
       
    }
}
