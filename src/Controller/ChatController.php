<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Message;
use App\Entity\Poll;
use App\Entity\PollVote;
use App\Entity\PollOption;
use App\Entity\User;
use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/chat')]
class ChatController extends AbstractController
{
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
    public function newMessage(Request $request, Chat $chat, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $message = new Message();
        $message->setChat_id($chat);
        $message->setContenu($request->request->get('contenu'));
        $message->setType($request->request->get('type', 'QUESTION'));
        $message->setPost_time(new \DateTime());
        
        // Set user ID 6 as the posted_by user
        $user = $userRepository->find(6);
        if ($user) {
            $message->setPosted_by($user);
        }

        $entityManager->persist($message);
        $entityManager->flush();

        return $this->redirectToRoute('app_chat_show', ['id' => $chat->getId()]);
    }

    #[Route('/{id}/poll/new', name: 'app_chat_poll_new', methods: ['POST'])]
    public function newPoll(Request $request, Chat $chat, EntityManagerInterface $entityManager): Response
    {
        $poll = new Poll();
        $poll->setChat_id($chat);
        $poll->setQuestion($request->request->get('question'));
        $poll->setIs_closed(false);
        $poll->setCreated_at(new \DateTime());

        $entityManager->persist($poll);
        $entityManager->flush();

        // Add poll options
        $options = $request->request->all('options');
        foreach ($options as $optionText) {
            $option = new PollOption();
            $option->setPoll_id($poll);
            $option->setText($optionText);
            $option->setVote_count(0);
            
            $entityManager->persist($option);
        }
        
        $entityManager->flush();

        return $this->redirectToRoute('app_chat_show', ['id' => $chat->getId()]);
    }

    #[Route('/poll/{id}/vote', name: 'app_poll_vote', methods: ['POST'])]
    public function vote(Request $request, Poll $poll, EntityManagerInterface $entityManager): Response
    {
        $optionId = $request->request->get('option_id');
        $option = $entityManager->getRepository(PollOption::class)->find($optionId);

        if ($option && !$poll->getIs_closed()) {
            $poll_vote = new PollVote();
            $poll_vote->setPoll_id($poll);
            $poll_vote->setOption_id($option);
            // Assuming you have a way to get the current user
            // $poll_vote->setUser_id($this->getUser());

            $option->setVote_count($option->getVote_count() + 1);

            $entityManager->persist($poll_vote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chat_show', ['id' => $poll->getChat_id()->getId()]);
    }

    #[Route('/poll/{id}/close', name: 'app_poll_close', methods: ['POST'])]
    public function closePoll(Poll $poll, EntityManagerInterface $entityManager): Response
    {
        $poll->setIs_closed(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_chat_show', ['id' => $poll->getChat_id()->getId()]);
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
                    'nom' => $message->getPosted_by()->getNom(),
                    'prenom' => $message->getPosted_by()->getPrenom()
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
                'poll_option' => []
            ];
            
            foreach ($poll->getPoll_option() as $option) {
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
} 