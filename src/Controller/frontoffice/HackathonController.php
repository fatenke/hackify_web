<?php

namespace App\Controller\frontoffice;

use App\Entity\Hackathon;
use App\Entity\Communaute;
use App\Entity\Chat;
use App\Repository\HackathonRepository;
use App\Repository\CommunauteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/hackathon')]
class HackathonController extends AbstractController
{
    private $entityManager;
    private $communauteRepository;

    public function __construct(EntityManagerInterface $entityManager, CommunauteRepository $communauteRepository)
    {
        $this->entityManager = $entityManager;
        $this->communauteRepository = $communauteRepository;
    }

    #[Route('/', name: 'app_hackathon_index', methods: ['GET'])]
    public function index(HackathonRepository $hackathonRepository): Response
    {
        return $this->render('hackathon/index.html.twig', [
            'hackathons' => $hackathonRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_hackathon_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $hackathon = new Hackathon();
        
        if ($request->isMethod('POST')) {
            try {
                $hackathon->setId_organisateur($request->request->get('id_organisateur'));
                $hackathon->setNom_hackathon($request->request->get('nom_hackathon'));
                $hackathon->setDescription($request->request->get('description'));
                $hackathon->setDate_debut(new \DateTime($request->request->get('date_debut')));
                $hackathon->setDate_fin(new \DateTime($request->request->get('date_fin')));
                $hackathon->setLieu($request->request->get('lieu'));
                $hackathon->setTheme($request->request->get('theme'));
                $hackathon->setConditions_participation($request->request->get('conditions_participation'));

                $this->entityManager->persist($hackathon);
                $this->entityManager->flush();

                // Vérifier que l'ID du hackathon a bien été généré
                if ($hackathon->getId_hackathon()) {
                    // Création automatique de la communauté associée
                    $this->createCommunauteFromHackathon($hackathon);
                    $this->addFlash('success', 'Hackathon créé avec succès avec sa communauté associée.');
                } else {
                    $this->addFlash('error', 'Erreur lors de la création du hackathon: ID non généré.');
                }

                return $this->redirectToRoute('app_hackathon_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la création: ' . $e->getMessage());
            }
        }

        return $this->render('hackathon/new.html.twig', [
            'hackathon' => $hackathon,
        ]);
    }

    /**
     * Crée une nouvelle communauté basée sur un hackathon si elle n'existe pas déjà
     */
    private function createCommunauteFromHackathon(Hackathon $hackathon): ?Communaute
    {
        try {
            // Vérifier si une communauté existe déjà pour ce hackathon
            $existingCommunaute = $this->communauteRepository->findOneBy(['id_hackathon' => $hackathon->getId_hackathon()]);
            
            if ($existingCommunaute) {
                return $existingCommunaute; // Retourner la communauté existante
            }

            // Création de la communauté
            $communaute = new Communaute();
            $communaute->setId_hackathon($hackathon->getId_hackathon());
            $communaute->setNom($hackathon->getNom_hackathon() . ' Community');
            $communaute->setDescription('Community for ' . $hackathon->getNom_hackathon() . ' - ' . $hackathon->getTheme());
            $communaute->setDate_creation(new \DateTime());

            $this->entityManager->persist($communaute);
            $this->entityManager->flush();

            // Création des chats par défaut
            $this->createDefaultChats($communaute);

            return $communaute;
        } catch (\Exception $e) {
            // Log l'erreur mais ne bloque pas la création du hackathon
            error_log('Erreur lors de la création de la communauté pour le hackathon ' . $hackathon->getId_hackathon() . ': ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Crée les chats par défaut pour une communauté
     */
    private function createDefaultChats(Communaute $communaute): void
    {
        try {
            // Types et noms des chats par défaut
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
                try {
                    $chat->setCommunaute_id($communaute);
                    $chat->setNom($chatNames[$index]);
                    $chat->setType($type);
                    $chat->setDate_creation(new \DateTime());
                    
                    $this->entityManager->persist($chat);
                } catch (\Exception $e) {
                    // Log l'erreur mais continue avec les autres chats
                    error_log('Erreur lors de la création du chat ' . $chatNames[$index] . ': ' . $e->getMessage());
                }
            }
            
            $this->entityManager->flush();
        } catch (\Exception $e) {
            // Log l'erreur mais ne bloque pas la création du hackathon
            error_log('Erreur lors de la création des chats pour la communauté ' . $communaute->getId() . ': ' . $e->getMessage());
        }
    }

    #[Route('/{id}', name: 'app_hackathon_show', methods: ['GET'])]
    public function show(Hackathon $hackathon): Response
    {
        return $this->render('hackathon/show.html.twig', [
            'hackathon' => $hackathon,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_hackathon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hackathon $hackathon): Response
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

            $this->entityManager->flush();

            return $this->redirectToRoute('app_hackathon_index');
        }

        return $this->render('hackathon/edit.html.twig', [
            'hackathon' => $hackathon,
        ]);
    }

    #[Route('/{id}', name: 'app_hackathon_delete', methods: ['POST'])]
    public function delete(Request $request, Hackathon $hackathon): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hackathon->getId_hackathon(), $request->request->get('_token'))) {
            $this->entityManager->remove($hackathon);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_hackathon_index');
    }
} 