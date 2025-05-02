<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Reaction;
use App\Repository\ReactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reaction')]
class ReactionController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ReactionRepository $reactionRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ReactionRepository $reactionRepository
    ) {
        $this->entityManager = $entityManager;
        $this->reactionRepository = $reactionRepository;
    }

    #[Route('/add/{messageId}', name: 'app_reaction_add', methods: ['POST'])]
    public function addReaction(Request $request, int $messageId): JsonResponse
    {
        // Check if user is logged in
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'You must be logged in to react to messages'], 401);
        }

        // Get emoji from request
        $emoji = $request->request->get('emoji');
        if (!$emoji) {
            return $this->json(['error' => 'Emoji is required'], 400);
        }

        // Validate emoji (basic validation)
        if (strlen($emoji) > 20) {
            return $this->json(['error' => 'Invalid emoji format'], 400);
        }

        // Find the message
        $message = $this->entityManager->getRepository(Message::class)->find($messageId);
        if (!$message) {
            return $this->json(['error' => 'Message not found'], 404);
        }

        // Check if user already reacted with this emoji
        $existingReaction = $this->entityManager->getRepository(Reaction::class)->findOneBy([
            'message' => $message,
            'user' => $user,
            'emoji' => $emoji
        ]);

        if ($existingReaction) {
            // Remove reaction if it already exists (toggle behavior)
            $this->entityManager->remove($existingReaction);
            $this->entityManager->flush();

            return $this->json([
                'success' => true,
                'action' => 'removed',
                'counts' => $this->reactionRepository->getReactionCounts($messageId)
            ]);
        }

        // Create and save the reaction
        $reaction = new Reaction();
        $reaction->setMessage($message);
        $reaction->setUser($user);
        $reaction->setEmoji($emoji);
        $reaction->setCreatedAt(new \DateTime());

        $this->entityManager->persist($reaction);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'action' => 'added',
            'counts' => $this->reactionRepository->getReactionCounts($messageId)
        ]);
    }

    #[Route('/get/{messageId}', name: 'app_reaction_get', methods: ['GET'])]
    public function getReactions(int $messageId): JsonResponse
    {
        // Find the message
        $message = $this->entityManager->getRepository(Message::class)->find($messageId);
        if (!$message) {
            return $this->json(['error' => 'Message not found'], 404);
        }

        // Get reaction counts
        $counts = $this->reactionRepository->getReactionCounts($messageId);

        // Check if current user has reacted
        $userReactions = [];
        if ($this->getUser()) {
            $userReactions = $this->entityManager->getRepository(Reaction::class)->findBy([
                'message' => $message,
                'user' => $this->getUser()
            ]);

            $userReactions = array_map(function(Reaction $reaction) {
                return $reaction->getEmoji();
            }, $userReactions);
        }

        return $this->json([
            'success' => true,
            'counts' => $counts,
            'userReactions' => $userReactions
        ]);
    }

    #[Route('/stats', name: 'app_reaction_stats', methods: ['GET'])]
    public function getStats(): JsonResponse
    {
        // Only allow admins to access statistics
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        // Get most used emojis
        $mostUsedEmojis = $this->reactionRepository->getMostUsedEmojis(10);

        return $this->json([
            'success' => true,
            'mostUsedEmojis' => $mostUsedEmojis
        ]);
    }
} 