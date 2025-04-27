<?php

namespace App\Controller;
use App\Entity\Chat;
use App\Entity\Message;
use App\Entity\Poll;
use App\Entity\PollOption;
use App\Entity\PollVote;
use App\Entity\User;
use App\Entity\Communaute;
use App\Form\MessageSearchType;
use App\Repository\ChatRepository;
use App\Security\Voter\ChatMessageVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/chat')]
class ChatController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/', name: 'app_chat_index', methods: ['GET'])]
    public function index(ChatRepository $chatRepository): Response
    {
        return $this->render('chat/index.html.twig', [
            'chats' => $chatRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_chat_show', methods: ['GET'])]
    public function show(Chat $chat): Response
    {
        return $this->render('chat/show.html.twig', [
            'chat' => $chat,
            'messages' => $chat->getMessages(),
            'polls' => $chat->getPolls(),
        ]);
    }

    #[Route('/{id}/message/new', name: 'app_chat_message_new', methods: ['POST'])]
    public function newMessage(Request $request, Chat $chat, EntityManagerInterface $entityManager): Response
    {
        // Check if user is authenticated
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'You must be logged in to send messages'], 401);
        }

        // Check if the user has permission to post in this chat type
        if (!$this->isGranted(ChatMessageVoter::POST_MESSAGE, $chat)) {
            return $this->json(['error' => 'You do not have permission to post in this chat type'], 403);
        }

        try {
            $message = new Message();
            $message->setChat_id($chat);
            $message->setContenu($request->request->get('contenu'));
            $message->setType($request->request->get('type', 'QUESTION'));
            $message->setPost_time(new \DateTime());
            $message->setPosted_by($user);
            
            // Don't set ID manually, let it be auto-incremented
            // If your database doesn't support auto-increment, uncomment the following:
            $nextId = $entityManager->createQuery('SELECT MAX(m.id) FROM App\Entity\Message m')->getSingleScalarResult();
            $message->setId(($nextId ?? 0) + 1);

            $entityManager->persist($message);
            $entityManager->flush();

            return $this->json(['success' => true, 'message' => 'Message sent successfully']);
        } catch (\Exception $e) {
            // Log the error
            error_log('Error sending message: ' . $e->getMessage());
            
            // Return detailed error for debugging
            return $this->json([
                'error' => 'Error sending message: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    #[Route('/{id}/poll/new', name: 'app_chat_poll_new', methods: ['POST'])]
    public function newPoll(Request $request, Chat $chat, EntityManagerInterface $entityManager): Response
    {
        // Check if user is authenticated
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'You must be logged in to create polls'], 401);
        }

        try {
            $poll = new Poll();
            $poll->setChat_id($chat);
            $poll->setQuestion($request->request->get('question'));
            $poll->setIs_closed(false);
            $poll->setCreated_at(new \DateTime());
            
            // Don't set created_by since the column doesn't exist in the database
            // We'll add it as part of a future migration
            
            // If your database doesn't support auto-increment, uncomment the following:
            $nextId = $entityManager->createQuery('SELECT MAX(p.id) FROM App\Entity\Poll p')->getSingleScalarResult();
            $poll->setId(($nextId ?? 0) + 1);

            $entityManager->persist($poll);
            $entityManager->flush();

            // Add poll options
            $options = $request->request->all('options');
            foreach ($options as $optionText) {
                if (!empty(trim($optionText))) {
                    $option = new PollOption();
                    $option->setPoll_id($poll);
                    $option->setText($optionText);
                    $option->setVote_count(0);
                    
                    $entityManager->persist($option);
                }
            }
            
            $entityManager->flush();

            return $this->json(['success' => true, 'message' => 'Poll created successfully']);
        } catch (\Exception $e) {
            // Log the error
            error_log('Error creating poll: ' . $e->getMessage());
            
            // Return detailed error for debugging
            return $this->json([
                'error' => 'Error creating poll: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    #[Route('/poll/{id}/vote', name: 'app_poll_vote', methods: ['POST'])]
    #[Route('poll/{id}/vote', name: 'app_poll_vote_alt', methods: ['POST'])]
    public function vote(Request $request, Poll $poll, EntityManagerInterface $entityManager): Response
    {
        // Check if user is authenticated
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'You must be logged in to vote'], 401);
        }

        try {
            $data = json_decode($request->getContent(), true);
            $optionId = $data['option_id'] ?? null;
            
            if (!$optionId) {
                return $this->json(['error' => 'No option selected'], 400);
            }
            
            $option = $entityManager->getRepository(PollOption::class)->find($optionId);

            if (!$option) {
                return $this->json(['error' => 'Option not found'], 404);
            }

            if ($poll->getIs_closed()) {
                return $this->json(['error' => 'Poll is closed'], 400);
            }

            // Check if user has already voted
            $existingVote = $entityManager->getRepository(PollVote::class)->findOneBy([
                'poll_id' => $poll,
                'user_id' => $user
            ]);

            if ($existingVote) {
                return $this->json(['error' => 'You have already voted'], 400);
            }

            $vote = new PollVote();
            $vote->setPoll_id($poll);
            $vote->setOption_id($option);
            $vote->setUser_id($user);

            $option->setVote_count($option->getVote_count() + 1);

            $entityManager->persist($vote);
            $entityManager->flush();

            return $this->json(['success' => true, 'message' => 'Vote recorded successfully']);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Error processing vote: ' . $e->getMessage()], 500);
        }
    }

    #[Route('/poll/{id}/close', name: 'app_poll_close', methods: ['POST'])]
    #[Route('poll/{id}/close', name: 'app_poll_close_alt', methods: ['POST'])]
    public function closePoll(Poll $poll, EntityManagerInterface $entityManager): Response
    {
        // Check if user is authenticated
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'You must be logged in to close polls'], 401);
        }
        
        // Allow admins and organizers to close polls
        if (!in_array('ROLE_ADMIN', $user->getRoles()) && !in_array('ROLE_ORGANISATEUR', $user->getRoles())) {
            return $this->json(['error' => 'You do not have permission to close this poll'], 403);
        }

        try {
            $poll->setIs_closed(true);
            $entityManager->flush();
            return $this->json(['success' => true, 'message' => 'Poll closed successfully']);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Error closing poll: ' . $e->getMessage()], 500);
        }
    }

    #[Route('/{id}/messages', name: 'app_chat_messages', methods: ['GET'])]
    public function getMessages(Chat $chat): Response
    {
        $messages = $chat->getMessages();
        $data = [];
        
        foreach ($messages as $message) {
            $data[] = [
                'id' => $message->getId(),
                'contenu' => $message->getContenu(),
                'type' => $message->getType(),
                'post_time' => $message->getPost_time()->format('Y-m-d H:i:s'),
                'posted_by' => [
                    'id_user' => $message->getPosted_by()->getId(),
                    'nom' => $message->getPosted_by()->getNomUser(),
                    'prenom' => $message->getPosted_by()->getPrenomUser()
                ]
            ];
        }
        
        return $this->json(['messages' => $data]);
    }

    #[Route('/{id}/polls', name: 'app_chat_polls', methods: ['GET'])]
    public function getPolls(Chat $chat): Response
    {
        $polls = $chat->getPolls();
        $data = [];
        
        foreach ($polls as $poll) {
            $pollData = [
                'id' => $poll->getId(),
                'question' => $poll->getQuestion(),
                'is_closed' => $poll->getIs_closed(),
                'created_at' => $poll->getCreated_at()->format('Y-m-d H:i:s'),
                'poll_option' => []
            ];
            
            foreach ($poll->getPollOptions() as $option) {
                $pollData['poll_option'][] = [
                    'id' => $option->getId(),
                    'text' => $option->getText(),
                    'vote_count' => $option->getVote_count()
                ];
            }
            
            $data[] = $pollData;
        }
        
        return $this->json(['polls' => $data]);
    }
    #[Route('/community/{id}/search', name: 'chat_search')]
    public function searchMessages(Request $request, Communaute $community): Response
    {
        $form = $this->createForm(MessageSearchType::class);
        $form->handleRequest($request);
        $results = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->get('query')->getData();
            if ($query) {
                // Use database search since Algolia is not properly configured
                $results = $this->performDatabaseSearch($query, $community);
            }
        }

        return $this->render('chat/search.html.twig', [
            'community' => $community,
            'form' => $form->createView(),
            'results' => $results,
        ]);
    }
    
    private function performDatabaseSearch(string $query, Communaute $community): array
    {
        // Escape special characters for LIKE query
        $escapedQuery = str_replace(['%', '_', '*'], ['\\%', '\\_', '%'], $query);
        
        return $this->entityManager->getRepository(Message::class)
            ->createQueryBuilder('m')
            ->join('m.chat_id', 'c')
            ->join('c.communaute_id', 'co')
            ->where('co.id = :communityId')
            ->andWhere('m.contenu LIKE :query')
            ->setParameter('communityId', $community->getId())
            ->setParameter('query', '%'.$escapedQuery.'%')
            ->orderBy('m.post_time', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }
    
    #[Route('/community/{id}/search-ajax', name: 'chat_search_ajax')]
    public function searchMessagesAjax(Request $request, Communaute $community): Response
    {
        try {
            $query = $request->query->get('query');
            $results = [];
            
            if ($query) {
                // Log the search request
                error_log("Performing search for '{$query}' in community {$community->getId()}");
                
                // Special case for * which would match everything
                if ($query === '*') {
                    // Return most recent messages instead
                    $results = $this->entityManager->getRepository(Message::class)
                        ->createQueryBuilder('m')
                        ->join('m.chat_id', 'c')
                        ->join('c.communaute_id', 'co')
                        ->where('co.id = :communityId')
                        ->setParameter('communityId', $community->getId())
                        ->orderBy('m.post_time', 'DESC')
                        ->setMaxResults(20)
                        ->getQuery()
                        ->getResult();
                } else {
                    $results = $this->performDatabaseSearch($query, $community);
                }
                
                error_log("Search completed with " . count($results) . " results");
            }
            
            // Transform results to be JSON-serializable
            $serializedResults = [];
            foreach ($results as $message) {
                $serializedResults[] = [
                    'id' => $message->getId(),
                    'contenu' => $message->getContenu(),
                    'type' => $message->getType(),
                    'post_time' => $message->getPost_time()->format('Y-m-d H:i:s'),
                    'chat_id' => [
                        'id' => $message->getChat_id()->getId(),
                        'nom' => $message->getChat_id()->getNom()
                    ],
                    'posted_by' => [
                        'id' => $message->getPosted_by()->getId(),
                        'nomUser' => $message->getPosted_by()->getNomUser(),
                        'prenomUser' => $message->getPosted_by()->getPrenomUser()
                    ]
                ];
            }
            
            return $this->json([
                'success' => true,
                'query' => $query,
                'results' => $serializedResults
            ]);
        } catch (\Exception $e) {
            // Log the error
            error_log("Search error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            
            // Return error response
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
                'query' => $request->query->get('query')
            ], 500);
        }
    }
}
