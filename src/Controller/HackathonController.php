<?php

namespace App\Controller;

/*use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;*/
use App\Entity\Hackathon;
use App\Form\HackathonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HackathonRepository;


final class HackathonController extends AbstractController
{
    /*#[Route('/hackathon', name: 'app_hackathon')]
    public function index(): Response
    {
        return $this->render('hackathon/index.html.twig', [
            'controller_name' => 'HackathonController',
        ]);
    }*/
    #[Route('ajouter', name: 'ajouter_hackathon')]
    public function ajouter(Request $request, EntityManagerInterface $em): Response
    {
        $hackathon = new Hackathon();
        $form = $this->createForm(HackathonType::class, $hackathon);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($hackathon);
            $em->flush();

            return $this->redirectToRoute('liste_hackathon'); 
        }

        return $this->render('hackathon/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/hackathon', name: 'liste_hackathon')]
    public function index(HackathonRepository $hackathonRepository): Response
    {
        $hackathons = $hackathonRepository->findAll();
    
        return $this->render('hackathon/afficher.html.twig', [
            'hackathons' => $hackathons,
        ]);
    }
    
    
    #[Route('/hackathon/modifier/{id}', name: 'modifier_hackathon')]
    public function modifier(Request $request, Hackathon $hackathon, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(HackathonType::class, $hackathon);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
    
            return $this->redirectToRoute('liste_hackathon');
        }
    
        return $this->render('hackathon/modifier.html.twig', [
            'form' => $form->createView(),
            'hackathon' => $hackathon,
        ]);
    }
    #[Route('/hackathon/supprimer/{id}', name: 'supprimer_hackathon', methods: ['POST'])]
    public function supprimer(Request $request, Hackathon $hackathon, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('supprimer'.$hackathon->getId_hackathon(), $request->request->get('_token'))) {
            $em->remove($hackathon);
            $em->flush();
        }
    
        return $this->redirectToRoute('liste_hackathon');
    }

    

    

}
