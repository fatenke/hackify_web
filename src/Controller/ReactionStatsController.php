<?php

namespace App\Controller;

use App\Repository\ReactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReactionStatsController extends AbstractController
{
    private ReactionRepository $reactionRepository;

    public function __construct(ReactionRepository $reactionRepository)
    {
        $this->reactionRepository = $reactionRepository;
    }

    #[Route('/backoffice/reaction-stats', name: 'app_reaction_stats')]
    public function index(): Response
    {
        // Check admin access
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Only admins can access this page.');
        }

        // Get most used emojis
        $mostUsedEmojis = $this->reactionRepository->getMostUsedEmojis();

        // Get total reaction count
        $totalReactions = array_reduce($mostUsedEmojis, function($carry, $item) {
            return $carry + $item['count'];
        }, 0);

        return $this->render('backoffice/reaction_stats.html.twig', [
            'mostUsedEmojis' => $mostUsedEmojis,
            'totalReactions' => $totalReactions,
        ]);
    }
} 