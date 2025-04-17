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
use App\Form\CommunauteType;

#[Route('/communaute')]
class CommunauteController extends AbstractController
{
    #[Route('/all', name: 'app_communaute_index', methods: ['GET'])]
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
        $form = $this->createForm(CommunauteType::class, $communaute);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $communaute->setDate_creation(new \DateTime());
            $entityManager->persist($communaute);

            // Create 5 default chats
            $chatTypes = ['ANNOUNCEMENT', 'QUESTION', 'FEEDBACK', 'COACH', 'BOT_SUPPORT'];
            $chatNames = ['Announcements', 'Questions', 'Feedback', 'Coaching', 'Bot Support'];

            foreach ($chatTypes as $index => $type) {
                $chat = new Chat();
                $chat->setCommunaute_id($communaute);
                $chat->setNom($chatNames[$index]);
                $chat->setType($type);
                $chat->setDate_creation(new \DateTime());
                $chat->setIs_active(true);
                $entityManager->persist($chat);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_communaute_index');
        }

        return $this->render('communaute/new.html.twig', [
            'form' => $form->createView(),
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

    #[Route('/user/{id}/communities', name: 'app_communaute_by_user', methods: ['GET'])]
    public function getCommunitiesByUser(int $id, CommunauteRepository $communauteRepository): Response
    {
        $communautes = $communauteRepository->findByUserId($id);

        return $this->render('communaute/by_user.html.twig', [
            'communautes' => $communautes,
            'user_id' => $id,
        ]);
    }
}