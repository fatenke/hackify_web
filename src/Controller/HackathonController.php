<?php

namespace App\Controller;

use App\Entity\Hackathon;
use App\Entity\Communaute;
use App\Entity\Chat;
use App\Repository\HackathonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/hackathon')]
class HackathonController extends AbstractController
{
    #[Route('/', name: 'app_hackathon_index', methods: ['GET'])]
    public function index(HackathonRepository $hackathonRepository): Response
    {
        return $this->render('hackathon/index.html.twig', [
            'hackathons' => $hackathonRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_hackathon_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $hackathon = new Hackathon();
        
        if ($request->isMethod('POST')) {
            $hackathon->setId_organisateur($request->request->get('id_organisateur'));
            $hackathon->setNom_hackathon($request->request->get('nom_hackathon'));
            $hackathon->setDescription($request->request->get('description'));
            $hackathon->setDate_debut(new \DateTime($request->request->get('date_debut')));
            $hackathon->setDate_fin(new \DateTime($request->request->get('date_fin')));
            $hackathon->setLieu($request->request->get('lieu'));
            $hackathon->setTheme($request->request->get('theme'));
            $hackathon->setConditions_participation($request->request->get('conditions_participation'));

            $entityManager->persist($hackathon);
            $entityManager->flush();

            // Create associated community
            $communaute = new Communaute();
            $communaute->setId_hackathon(0); // Set to 0 as requested
            $communaute->setNom($hackathon->getNom_hackathon() . ' Community');
            $communaute->setDescription('Community for ' . $hackathon->getNom_hackathon());
            $communaute->setDate_creation(new \DateTime());

            $entityManager->persist($communaute);
            $entityManager->flush();

            // Create 5 chats for the community
            $chatTypes = ['ANNOUNCEMENT', 'QUESTION', 'FEEDBACK', 'COACH', 'BOT_SUPPORT'];
            $chatNames = [
                'Announcements',
                'Questions',
                'Feedback',
                'Coaching',
                'Bot Support'
            ];

            foreach ($chatTypes as $index => $type) {
                $chat = new Chat();
                $chat->setCommunaute_id($communaute);
                $chat->setNom($chatNames[$index]);
                $chat->setType($type);
                $chat->setDate_creation(new \DateTime());
                
                $entityManager->persist($chat);
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('app_hackathon_index');
        }

        return $this->render('hackathon/new.html.twig', [
            'hackathon' => $hackathon,
        ]);
    }

    #[Route('/{id}', name: 'app_hackathon_show', methods: ['GET'])]
    public function show(Hackathon $hackathon): Response
    {
        return $this->render('hackathon/show.html.twig', [
            'hackathon' => $hackathon,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_hackathon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hackathon $hackathon, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $hackathon->setId_organisateur($request->request->get('id_organisateur'));
            $hackathon->setNom_hackathon($request->request->get('nom_hackathon'));
            $hackathon->setDescription($request->request->get('description'));
            $hackathon->setDate_debut(new \DateTime($request->request->get('date_debut')));
            $hackathon->setDate_fin(new \DateTime($request->request->get('date_fin')));
            $hackathon->setLieu($request->request->get('lieu'));
            $hackathon->setTheme($request->request->get('theme'));
            $hackathon->setConditions_participation($request->request->get('conditions_participation'));

            $entityManager->flush();

            return $this->redirectToRoute('app_hackathon_index');
        }

        return $this->render('hackathon/edit.html.twig', [
            'hackathon' => $hackathon,
        ]);
    }

    #[Route('/{id}', name: 'app_hackathon_delete', methods: ['POST'])]
    public function delete(Request $request, Hackathon $hackathon, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hackathon->getId_hackathon(), $request->request->get('_token'))) {
            $entityManager->remove($hackathon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_hackathon_index');
    }
} 