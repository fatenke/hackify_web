<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Chat;
use App\Entity\Message;
use App\Enum\ChatType;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class ChatMessageVoter extends Voter
{
    const POST_MESSAGE = 'post_message';
    
    private $security;
    
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    protected function supports(string $attribute, $subject): bool
    {
        // Only support the POST_MESSAGE attribute
        if ($attribute !== self::POST_MESSAGE) {
            return false;
        }
        
        // Only vote on Chat objects
        if (!$subject instanceof Chat) {
            return false;
        }
        
        return true;
    }
    
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        
        // If the user is not logged in, deny access
        if (!$user instanceof User) {
            return false;
        }
        
        /** @var Chat $chat */
        $chat = $subject;
        
        switch ($attribute) {
            case self::POST_MESSAGE:
                return $this->canPostMessage($chat, $user);
        }
        
        throw new \LogicException('This code should not be reached!');
    }
    
    private function canPostMessage(Chat $chat, User $user): bool
    {
        // Get the chat type
        $chatType = $chat->getType();
        
        // Get user roles
        $roles = $user->getRoles();
        
        // Check permissions based on chat type
        switch ($chatType) {
            case ChatType::ANNOUNCEMENT->value:
                // Only organizers can post in announcement chats
                return in_array('ROLE_ORGANISATEUR', $roles);
                
            case ChatType::FEEDBACK->value:
                // Only participants can post in feedback chats
                return in_array('ROLE_PARTICIPANT', $roles);
                
            case ChatType::COACH->value:
                // Only coaches can post in coach chats
                return in_array('ROLE_COACH', $roles);
                
            case ChatType::QUESTION->value:
                // All users can post in question chats
                return true;
                
            case ChatType::BOT_SUPPORT->value:
                // All users can post in bot support chats
                return true;
                
            default:
                // For any undefined chat type, deny by default
                return false;
        }
    }
} 