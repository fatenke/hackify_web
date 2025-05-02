<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Chat;
use App\Repository\MessageRepository;
use App\Security\Voter\ChatMessageVoter;
use App\Service\PerspectiveApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/message')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'app_message_index', methods: ['GET'])]
    public function index(MessageRepository $messageRepository): Response
    {
        return $this->render('message/index.html.twig', [
            'messages' => $messageRepository->findAll(),
        ]);
    }

    #[Route('/new/{chat_id}', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Chat $chat_id, EntityManagerInterface $entityManager, PerspectiveApiService $perspectiveApiService): Response
    {
        // Check if the current user has permission to post in this chat
        $this->denyAccessUnlessGranted(ChatMessageVoter::POST_MESSAGE, $chat_id, 'You do not have permission to post in this chat.');
        
        $message = new Message();
        
        if ($request->isMethod('POST')) {
            $content = $request->request->get('contenu');
            
            // Check message content with Perspective API
            $contentAnalysis = $perspectiveApiService->analyzeText($content);
            
            if ($contentAnalysis['isFlagged']) {
                $this->addFlash('error', 'Your message may contain inappropriate content and cannot be posted.');
                
                // You can optionally pass the toxicity scores to display to the user
                return $this->render('message/new.html.twig', [
                    'chat_id' => $chat_id,
                    'toxicity_scores' => $contentAnalysis['scores']
                ]);
            }
            
            $message->setChat_id($chat_id);
            $message->setContenu($content);
            $message->setType($request->request->get('type', 'QUESTION'));
            $message->setPost_time(new \DateTime());
            // Set the current user as the message sender
            $message->setPosted_by($this->getUser());

            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_chat_show', ['id' => $chat_id->getId()]);
        }
        
        return $this->render('message/new.html.twig', [
            'chat_id' => $chat_id
        ]);
    }

    #[Route('/{id}', name: 'app_message_show', methods: ['GET'])]
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        // Only allow the message creator or admins to edit a message
        if ($message->getPosted_by() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('You cannot edit this message.');
        }
        
        if ($request->isMethod('POST')) {
            $message->setContenu($request->request->get('contenu'));
            $message->setType($request->request->get('type'));

            $entityManager->flush();

            return $this->redirectToRoute('app_chat_show', ['id' => $message->getChat_id()->getId()]);
        }

        return $this->render('message/edit.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/{id}', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        // Only allow the message creator or admins to delete a message
        if ($message->getPosted_by() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('You cannot delete this message.');
        }
        
        $chatId = $message->getChat_id()->getId();
        
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chat_show', ['id' => $chatId]);
    }
} 