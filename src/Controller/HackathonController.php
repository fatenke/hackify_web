<?php

namespace App\Controller;


use App\Entity\Hackathon;
use App\Form\HackathonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HackathonRepository;
use App\Repository\ParticipationRepository;



final class HackathonController extends AbstractController
{
    /*#[Route('/hackathon', name: 'app_hackathon')]
    public function index(): Response
    {
        return $this->render('hackathon/index.html.twig', [
            'controller_name' => 'HackathonController',
        ]);
    }*/
    #[Route('hackathon/ajouter', name: 'ajouter_hackathon')]
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
    #[Route('hackathon', name: 'liste_hackathon')]
    public function liste(
    HackathonRepository $hackathonRepository,
    ParticipationRepository $participationRepository
    ): Response {
    $hackathons = $hackathonRepository->findAll();
    $participations = $participationRepository->findAll();

    return $this->render('hackathon/afficher.html.twig', [
        'hackathons' => $hackathons,
        'participations' => $participations
    ]);
}

    #[Route('hackathon/{id}', name:'hackathon_details')]
    public function details($id, HackathonRepository $hackathonRepository): Response
    {
        // Trouver le hackathon par son ID
        $hackathon = $hackathonRepository->find($id);

        // Si le hackathon n'est pas trouvé, rediriger vers la liste des hackathons
        if (!$hackathon) {
            return $this->redirectToRoute('hackathons_index');
        }

        return $this->render('hackathon/details.html.twig', [
            'hackathon' => $hackathon,
        ]);
    }
    
    
    #[Route('hackathon/modifier/{id}', name: 'modifier_hackathon')]
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
