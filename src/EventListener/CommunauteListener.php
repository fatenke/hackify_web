<?php

namespace App\EventListener;

use App\Entity\Chat;
use App\Entity\Communaute;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CommunauteListener
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Communaute) {
            return;
        }

        // Create default chats
        $defaultChatTypes = [
            Chat::ANNOUNCEMENT => 'Annonces',
            Chat::QUESTION => 'Questions',
            Chat::FEEDBACK => 'Retours',
            Chat::COACH => 'Coach',
            Chat::BOT_SUPPORT => 'Support Bot'
        ];

        foreach ($defaultChatTypes as $type => $name) {
            $chat = new Chat();
            $chat->setCommunaute($entity);
            $chat->setNom($name);
            $chat->setType($type);

            $this->entityManager->persist($chat);
        }

        $this->entityManager->flush();
    }
} 