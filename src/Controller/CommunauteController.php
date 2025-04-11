<?php

namespace App\Controller;

use App\Entity\Communaute;
use App\Entity\Chat;
use App\Repository\CommunauteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/communaute')]
class CommunauteController extends AbstractController
{
    #[Route('/', name: 'app_communaute_index', methods: ['GET'])]
    public function index(CommunauteRepository $communauteRepository): Response
    {
        return $this->render('communaute/index.html.twig', [
            'communaute' => $communauteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_communaute_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $communaute = new Communaute();
        
        if ($request->isMethod('POST')) {
            $communaute->setId_hackathon($request->request->get('id_hackathon'));
            $communaute->setNom($request->request->get('nom'));
            $communaute->setDescription($request->request->get('description'));
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

            return $this->redirectToRoute('app_communaute_index');
        }

        return $this->render('communaute/new.html.twig', [
            'communaute' => $communaute,
        ]);
    }

    #[Route('/{id}', name: 'app_communaute_show', methods: ['GET'])]
    public function show(Communaute $communaute): Response
    {
        return $this->render('communaute/show.html.twig', [
            'communaute' => $communaute,
            'chats' => $communaute->getChats(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_communaute_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Communaute $communaute, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $communaute->setId_hackathon($request->request->get('id_hackathon'));
            $communaute->setNom($request->request->get('nom'));
            $communaute->setDescription($request->request->get('description'));

            $entityManager->flush();

            return $this->redirectToRoute('app_communaute_index');
        }

        return $this->render('communaute/edit.html.twig', [
            'communaute' => $communaute,
        ]);
    }

    #[Route('/{id}', name: 'app_communaute_delete', methods: ['POST'])]
    public function delete(Request $request, Communaute $communaute, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$communaute->getId(), $request->request->get('_token'))) {
            $entityManager->remove($communaute);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_communaute_index');
    }
} 