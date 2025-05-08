<?php

namespace App\Enum;

enum ChatType: string
{
    case ANNOUNCEMENT = 'ANNOUNCEMENT';
    case QUESTION = 'QUESTION';
    case FEEDBACK = 'FEEDBACK';
    case COACH = 'COACH';
    case BOT_SUPPORT = 'BOT_SUPPORT';

    /**
     * Get all chat types as an array
     * 
     * @return array
     */
    public static function getAll(): array
    {
        return [
            self::ANNOUNCEMENT->value,
            self::QUESTION->value,
            self::FEEDBACK->value, 
            self::COACH->value,
            self::BOT_SUPPORT->value,
        ];
    }
    
    /**
     * Get a descriptive label for each chat type
     * 
     * @return string
     */
    public function getLabel(): string
    {
        return match($this) {
            self::ANNOUNCEMENT => 'Annonces',
            self::QUESTION => 'Questions',
            self::FEEDBACK => 'Retour',
            self::COACH => 'Coaching',
            self::BOT_SUPPORT => 'Support',
        };
    }
    
    /**
     * Get descriptions for each chat type
     * 
     * @return string
     */
    public function getDescription(): string
    {
        return match($this) {
            self::ANNOUNCEMENT => 'Annonces officielles des organisateurs',
            self::QUESTION => 'Questions et réponses pour tous',
            self::FEEDBACK => 'Feedback et retours des participants',
            self::COACH => 'Communications avec les coachs',
            self::BOT_SUPPORT => 'Support technique automatisé',
        };
    }
} 