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
use App\Service\GeminiChatService;
use App\Service\PerspectiveApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    // --- Chat Management ---
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

    // --- Message Handling ---
    #[Route('/{id}/message/new', name: 'app_chat_message_new', methods: ['POST'])]
    public function newMessage(Request $request, Chat $chat, PerspectiveApiService $perspectiveApiService): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'You must be logged in to send messages'], 401);
        }

        if ($chat->getType() !== 'BOT_SUPPORT' && !$this->isGranted(ChatMessageVoter::POST_MESSAGE, $chat)) {
            return $this->json(['error' => 'You do not have permission to post in this chat type'], 403);
        }

        $content = $request->request->get('contenu');
        if (!$content) {
            return $this->json(['error' => 'Message content is required'], 400);
        }

        // Check message for toxic content using Perspective API
        $contentAnalysis = $perspectiveApiService->analyzeText($content);
        
        if ($contentAnalysis['isFlagged']) {
            // Message is flagged as toxic
            return $this->json([
                'error' => 'Your message may contain inappropriate content and cannot be posted.',
                'toxicity_scores' => $contentAnalysis['scores']
            ], 400);
        }

        $message = new Message();
        $message->setChat_id($chat);
        $message->setContenu($content);
        $message->setType($request->request->get('type', 'QUESTION'));
        $message->setPost_time(new \DateTime());
        $message->setPosted_by($user);

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Message sent successfully'
        ]);
    }

    #[Route('/{id}/message/gemini', name: 'app_chat_message_gemini', methods: ['POST'])]
    public function newGeminiMessage(Request $request, Chat $chat, GeminiChatService $geminiChatService, PerspectiveApiService $perspectiveApiService): JsonResponse
    {
        try {
            $user = $this->getUser();
            if (!$user) {
                return $this->json(['error' => 'You must be logged in to send messages'], 401);
            }

            if ($chat->getType() !== 'BOT_SUPPORT') {
                return $this->json(['error' => 'Gemini messages are only allowed in BOT_SUPPORT chats'], 403);
            }

            $content = $request->request->get('contenu');
            if (!$content) {
                return $this->json(['error' => 'Message content is required'], 400);
            }

            // Check message for toxic content using Perspective API
            $contentAnalysis = $perspectiveApiService->analyzeText($content);
            
            if ($contentAnalysis['isFlagged']) {
                // Message is flagged as toxic
                return $this->json([
                    'error' => 'Your message may contain inappropriate content and cannot be posted.',
                    'toxicity_scores' => $contentAnalysis['scores']
                ], 400);
            }

            // Save user message
            $message = new Message();
            $message->setChat_id($chat);
            $message->setContenu($content);
            $message->setType($request->request->get('type', 'QUESTION'));
            $message->setPost_time(new \DateTime());
            $message->setPosted_by($user);
            $this->entityManager->persist($message);
            $this->entityManager->flush(); // Flush immediately to ensure message is saved

            // Log path correction
            $logPath = __DIR__ . '/../../var/log/gemini_debug.log';
            file_put_contents($logPath, "Processing message ID: {$message->getId()} in chat ID: {$chat->getId()}\n", FILE_APPEND);

            // Load previous chat history from database
            $previousMessages = $this->entityManager->getRepository(Message::class)
                ->findBy(['chat_id' => $chat], ['post_time' => 'ASC']);

            // Convert messages to Gemini format
            $history = [];
            foreach ($previousMessages as $prevMessage) {
                // Skip the message we just saved (it will be added by the sendMessage method)
                if ($prevMessage->getId() === $message->getId()) {
                    continue;
                }
                
                $role = ($prevMessage->getPosted_by() && $prevMessage->getPosted_by()->getEmailUser() !== 'bot@hackify.com') 
                    ? 'user' 
                    : 'model';
                
                $history[] = [
                    'role' => $role,
                    'parts' => [['text' => $prevMessage->getContenu()]]
                ];
            }

            // Set the chat history in Gemini service
            if (!empty($history)) {
                file_put_contents($logPath, "Setting history with " . count($history) . " messages\n", FILE_APPEND);
                $geminiChatService->setHistory($history, $chat->getId());
            }

            // Get Gemini response
            try {
                $botResponse = $geminiChatService->sendMessage($content, $chat->getId());
                file_put_contents($logPath, "Successfully received bot response\n", FILE_APPEND);

                // Save bot response
                try {
                    // Find the bot user - now with a try/catch block
                    $botUser = null;
                    try {
                        $botUser = $this->entityManager->getRepository(User::class)->find(-1);
                        if ($botUser) {
                            file_put_contents($logPath, "Found existing bot user ID: -1\n", FILE_APPEND);
                        }
                    } catch (\Exception $e) {
                        file_put_contents($logPath, "Error finding bot user: {$e->getMessage()}\n", FILE_APPEND);
                    }

                    if (!$botUser) {
                        file_put_contents($logPath, "Creating new bot user\n", FILE_APPEND);
                        try {
                            $botUser = new User();
                            $botUser->setId(-1);
                            $botUser->setNomUser('Bot');
                            $botUser->setPrenomUser('Support');
                            $botUser->setEmailUser('bot@hackify.com');
                            $botUser->setMdpUser('botpassword'); 
                            $botUser->setRoles(['ROLE_BOT']);
                            $botUser->setTelUser(123456789);
                            $botUser->setAdresseUser('Bot Address');
                            $botUser->setStatusUser('Active');
                            $this->entityManager->persist($botUser);
                            $this->entityManager->flush();
                            file_put_contents($logPath, "Bot user created successfully\n", FILE_APPEND);
                        } catch (\Exception $e) {
                            file_put_contents($logPath, "Error creating bot user: {$e->getMessage()}\n", FILE_APPEND);
                            // If we can't create a bot user, we'll set it to null
                            $botUser = null;
                        }
                    }

                    // Create the bot message
                    $botMessage = new Message();
                    $botMessage->setChat_id($chat);
                    $botMessage->setContenu($botResponse);
                    $botMessage->setType('REPONSE');
                    $botMessage->setPost_time(new \DateTime());
                    $botMessage->setPosted_by($botUser); // This might be null if we couldn't create/find a bot user
                    
                    $this->entityManager->persist($botMessage);
                    file_put_contents($logPath, "Bot message created\n", FILE_APPEND);
                    
                    $this->entityManager->flush();
                    file_put_contents($logPath, "Changes flushed to database\n", FILE_APPEND);
                } catch (\Exception $e) {
                    file_put_contents($logPath, "Error saving bot message: {$e->getMessage()}\n{$e->getTraceAsString()}\n", FILE_APPEND);
                    // Even if saving fails, we'll return the response to the user
                }
            } catch (\Exception $e) {
                file_put_contents($logPath, "Gemini API error: {$e->getMessage()}\n", FILE_APPEND);
                $botResponse = 'Sorry, I encountered an error while processing your request.';
            }

            // Return success response even if saving the message failed
            return $this->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'bot_response' => $botResponse ?? 'Error: No response generated',
            ]);
        } catch (\Exception $e) {
            // Catch-all for any unexpected errors
            $errorMsg = "Unexpected error: {$e->getMessage()}\n{$e->getTraceAsString()}";
            file_put_contents(__DIR__ . '/../../var/log/gemini_debug.log', $errorMsg, FILE_APPEND);
            return $this->json([
                'error' => 'An unexpected error occurred. Please try again later.',
                'debug' => $e->getMessage() // Only include this in development
            ], 500);
        }
    }

    #[Route('/{id}/messages', name: 'app_chat_messages', methods: ['GET'])]
    public function getMessages(Chat $chat): Response
    {
        $messages = $chat->getMessages();
        $data = [];
        
        foreach ($messages as $message) {
            $messageData = [
                'id' => $message->getId(),
                'contenu' => $message->getContenu(),
                'type' => $message->getType(),
                'post_time' => $message->getPost_time()->format('Y-m-d H:i:s')
            ];
            
            // Add posted_by data if available
            if ($message->getPosted_by()) {
                $messageData['posted_by'] = [
                    'id_user' => $message->getPosted_by()->getId(),
                    'nom' => $message->getPosted_by()->getNomUser(),
                    'prenom' => $message->getPosted_by()->getPrenomUser(),
                    'email_user' => $message->getPosted_by()->getEmailUser(),
                    'photoUser' => $message->getPosted_by()->getPhotoUser()
                ];
            } else {
                $messageData['posted_by'] = null;
            }
            
            $data[] = $messageData;
        }
        
        return $this->json(['messages' => $data]);
    }

    // --- Poll Handling ---
    #[Route('/{id}/poll/new', name: 'app_chat_poll_new', methods: ['POST'])]
    public function newPoll(Request $request, Chat $chat): JsonResponse
    {
        try {
            $user = $this->getUser();
            if (!$user) {
                return $this->json(['error' => 'You must be logged in to create polls'], 401);
            }
            
            // Check user permissions
            if (!in_array('ROLE_ADMIN', $user->getRoles()) && !in_array('ROLE_ORGANISATEUR', $user->getRoles())) {
                return $this->json(['error' => 'You do not have permission to create polls'], 403);
            }
            
            // Debug request data
            $requestData = $request->request->all();
            
            // Check if question exists
            $question = $request->request->get('question');
            if (empty($question)) {
                return $this->json(['error' => 'Poll question is required'], 400);
            }
            
            // Create the poll
            $poll = new Poll();
            $poll->setChat_id($chat);
            $poll->setQuestion($question);
            $poll->setIs_closed(false);
            $poll->setCreated_at(new \DateTime());
            
            $this->entityManager->persist($poll);
            $this->entityManager->flush();
            
            // Add options
            $options = $request->request->all('options');
            if (empty($options)) {
                return $this->json(['error' => 'At least one option is required'], 400);
            }
            
            foreach ($options as $optionText) {
                if (!empty(trim($optionText))) {
                    $option = new PollOption();
                    $option->setPoll_id($poll);
                    $option->setText($optionText);
                    $option->setVote_count(0);
                    $this->entityManager->persist($option);
                }
            }
            
            $this->entityManager->flush();
            
            return $this->json([
                'success' => true, 
                'message' => 'Poll created successfully',
                'poll_id' => $poll->getId()
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Error creating poll: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    #[Route('/poll/{id}/vote', name: 'app_poll_vote', methods: ['POST'])]
    #[Route('poll/{id}/vote', name: 'app_poll_vote_alt', methods: ['POST'])]
    public function vote(Request $request, Poll $poll): JsonResponse
    {
        try {
            $user = $this->getUser();
            if (!$user) {
                return $this->json(['error' => 'You must be logged in to vote'], 401);
            }

            // Parse the request content based on content type
            $optionId = null;
            $contentType = $request->headers->get('Content-Type');
            
            if (str_contains($contentType, 'application/json')) {
                $data = json_decode($request->getContent(), true);
                $optionId = $data['option_id'] ?? null;
            } else {
                // Form data submission
                $optionId = $request->request->get('option_id');
            }

            if (!$optionId) {
                return $this->json(['error' => 'No option selected'], 400);
            }

            $option = $this->entityManager->getRepository(PollOption::class)->find($optionId);
            if (!$option) {
                return $this->json(['error' => 'Option not found'], 404);
            }

            if ($poll->getIs_closed()) {
                return $this->json(['error' => 'Poll is closed'], 400);
            }

            $existingVote = $this->entityManager->getRepository(PollVote::class)->findOneBy([
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

            // Update the vote count
            $option->setVote_count($option->getVote_count() + 1);

            $this->entityManager->persist($vote);
            $this->entityManager->flush();

            return $this->json([
                'success' => true, 
                'message' => 'Vote recorded successfully',
                'option_id' => $optionId
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Error recording vote: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/poll/{id}/close', name: 'app_poll_close', methods: ['POST'])]
    #[Route('poll/{id}/close', name: 'app_poll_close_alt', methods: ['POST'])]
    public function closePoll(Poll $poll): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'You must be logged in to close polls'], 401);
        }

        if (!in_array('ROLE_ADMIN', $user->getRoles()) && !in_array('ROLE_ORGANISATEUR', $user->getRoles())) {
            return $this->json(['error' => 'You do not have permission to close this poll'], 403);
        }

        $poll->setIs_closed(true);
        $this->entityManager->flush();
        return $this->json(['success' => true, 'message' => 'Poll closed successfully']);
    }

    #[Route('/{id}/polls', name: 'app_chat_polls', methods: ['GET'])]
    public function getPolls(Chat $chat): JsonResponse
    {
        try {
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
                    // Catch any issues with the PollOption entity
                    try {
                        $pollData['poll_option'][] = [
                            'id' => $option->getId(),
                            'text' => $option->getText(),
                            'vote_count' => $option->getVote_count() // Using the correct getter
                        ];
                    } catch (\Exception $e) {
                        error_log('Error processing poll option: ' . $e->getMessage());
                        // Continue with the next option
                        continue;
                    }
                }

                $data[] = $pollData;
            }

            return $this->json(['polls' => $data]);
        } catch (\Exception $e) {
            // Log the error for server-side debugging
            error_log('Error in getPolls: ' . $e->getMessage());
            error_log('Error trace: ' . $e->getTraceAsString());
            
            return $this->json([
                'error' => 'Error loading polls: ' . $e->getMessage()
            ], 500);
        }
    }

    // --- Search Functionality ---
    #[Route('/community/{id}/search', name: 'chat_search')]
    public function searchMessages(Request $request, Communaute $community): Response
    {
        $form = $this->createForm(MessageSearchType::class);
        $form->handleRequest($request);
        $results = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->get('query')->getData();
            if ($query) {
                $results = $this->performDatabaseSearch($query, $community);
            }
        }

        return $this->render('chat/search.html.twig', [
            'community' => $community,
            'form' => $form->createView(),
            'results' => $results,
        ]);
    }

    #[Route('/community/{id}/search-ajax', name: 'chat_search_ajax')]
    public function searchMessagesAjax(Request $request, Communaute $community): JsonResponse
    {
        $query = $request->query->get('query');
        $results = [];

        if ($query) {
            if ($query === '*') {
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
        }

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
    }

    #[Route('/community/{id}/members', name: 'app_community_members', methods: ['GET'])]
    public function getCommunityMembers(Communaute $community): JsonResponse
    {
        try {
            // Check if user is part of this community
            $currentUser = $this->getUser();
            if (!$currentUser) {
                return $this->json(['error' => 'You must be logged in to access community members'], 401);
            }

            // Get the hackathon associated with the community
            $hackathon = $community->getIdHackathon();
            if (!$hackathon) {
                return $this->json(['error' => 'No hackathon associated with this community'], 404);
            }

            // Get all participations for this hackathon
            $participations = $hackathon->getParticipations();
            
            $members = [];
            foreach ($participations as $participation) {
                // Get user from participation
                $user = $participation->getParticipant();
                if ($user) {
                    // Get the user's role - default to 'PARTICIPANT' if no roles
                    $roles = $user->getRoles();
                    $role = !empty($roles) ? $roles[0] : 'ROLE_PARTICIPANT';
                    
                    // Remove the 'ROLE_' prefix for display
                    $displayRole = str_replace('ROLE_', '', $role);
                    
                    $members[] = [
                        'id' => $user->getId(),
                        'name' => $user->getPrenomUser() . ' ' . $user->getNomUser(),
                        'email' => $user->getEmailUser(),
                        'photo' => $user->getPhotoUser(),
                        'role' => $displayRole
                    ];
                }
            }

            return $this->json([
                'success' => true,
                'members' => $members
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Failed to retrieve community members: ' . $e->getMessage()
            ], 500);
        }
    }

    private function performDatabaseSearch(string $query, Communaute $community): array
    {
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
}