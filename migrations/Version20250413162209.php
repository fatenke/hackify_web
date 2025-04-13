<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250413162209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update schema for multiple tables, including foreign keys and column definitions';
    }

    public function up(Schema $schema): void
    {
        // Create messenger_messages table
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (
                id BIGINT AUTO_INCREMENT NOT NULL,
                body LONGTEXT NOT NULL,
                headers LONGTEXT NOT NULL,
                queue_name VARCHAR(190) NOT NULL,
                created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
                INDEX IDX_75EA56E0FB7336F0 (queue_name),
                INDEX IDX_75EA56E0E3BD61CE (available_at),
                INDEX IDX_75EA56E016BA31DB (delivered_at),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);

        // Chapitres table: Update columns and foreign key
        $this->addSql(<<<'SQL'
            ALTER TABLE chapitres 
            CHANGE id id INT NOT NULL, 
            CHANGE id_ressources id_ressources INT DEFAULT NULL, 
            CHANGE contenu contenu LONGTEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapitres 
            ADD CONSTRAINT FK_chapitres_ressources 
            FOREIGN KEY (id_ressources) 
            REFERENCES ressources (id) 
            ON DELETE CASCADE
        SQL);

        // Chat table
        $this->addSql(<<<'SQL'
            ALTER TABLE chat 
            CHANGE id id INT NOT NULL, 
            CHANGE is_active is_active TINYINT(1) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chat 
            ADD CONSTRAINT FK_chat_communaute 
            FOREIGN KEY (communaute_id) 
            REFERENCES communaute (id) 
            ON DELETE CASCADE
        SQL);

        // Communaute table
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute 
            CHANGE id id INT NOT NULL, 
            CHANGE id_hackathon id_hackathon INT DEFAULT NULL, 
            CHANGE is_active is_active TINYINT(1) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute 
            ADD CONSTRAINT FK_communaute_hackathon 
            FOREIGN KEY (id_hackathon) 
            REFERENCES hackathon (id_hackathon) 
            ON DELETE CASCADE
        SQL);

        // Evaluation table
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation 
            ADD note_tech DOUBLE PRECISION NOT NULL, 
            ADD note_innov DOUBLE PRECISION NOT NULL, 
            DROP NoteTech, 
            DROP NoteInnov, 
            CHANGE id id INT NOT NULL, 
            CHANGE idJury idJury INT DEFAULT NULL, 
            CHANGE idHackathon idHackathon INT DEFAULT NULL, 
            CHANGE idProjet idProjet INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation 
            ADD CONSTRAINT FK_evaluation_hackathon 
            FOREIGN KEY (idHackathon) 
            REFERENCES hackathon (id_hackathon) 
            ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation 
            ADD CONSTRAINT FK_evaluation_projet 
            FOREIGN KEY (idProjet) 
            REFERENCES projets (id) 
            ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation 
            ADD CONSTRAINT FK_evaluation_jury 
            FOREIGN KEY (idJury) 
            REFERENCES user (id_user) 
            ON DELETE CASCADE
        SQL);

        // Hackathon table
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon 
            CHANGE id_hackathon id_hackathon INT NOT NULL, 
            CHANGE id_organisateur id_organisateur INT DEFAULT NULL, 
            CHANGE max_participants max_participants INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon 
            ADD CONSTRAINT FK_hackathon_organisateur 
            FOREIGN KEY (id_organisateur) 
            REFERENCES user (id_user) 
            ON DELETE CASCADE
        SQL);

        // Message table
        $this->addSql(<<<'SQL'
            ALTER TABLE message 
            CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message 
            ADD CONSTRAINT FK_message_chat 
            FOREIGN KEY (chat_id) 
            REFERENCES chat (id) 
            ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message 
            ADD CONSTRAINT FK_message_user 
            FOREIGN KEY (posted_by) 
            REFERENCES user (id_user) 
            ON DELETE CASCADE
        SQL);

        // Participation table
        $this->addSql(<<<'SQL'
            ALTER TABLE participation 
            CHANGE id_participation id_participation INT NOT NULL, 
            CHANGE id_hackathon id_hackathon INT DEFAULT NULL, 
            CHANGE id_participant id_participant INT DEFAULT NULL, 
            CHANGE date_inscription date_inscription DATETIME NOT NULL, 
            CHANGE statut statut VARCHAR(20) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation 
            ADD CONSTRAINT FK_participation_hackathon 
            FOREIGN KEY (id_hackathon) 
            REFERENCES hackathon (id_hackathon) 
            ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation 
            ADD CONSTRAINT FK_participation_user 
            FOREIGN KEY (id_participant) 
            REFERENCES user (id_user) 
            ON DELETE CASCADE
        SQL);

        // Poll_options table
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_options 
            CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_options 
            ADD CONSTRAINT FK_poll_options_poll 
            FOREIGN KEY (poll_id) 
            REFERENCES polls (id) 
            ON DELETE CASCADE
        SQL);

        // Poll_votes table
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes 
            CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes 
            ADD CONSTRAINT FK_poll_votes_poll 
            FOREIGN KEY (poll_id) 
            REFERENCES polls (id) 
            ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes 
            ADD CONSTRAINT FK_poll_votes_option 
            FOREIGN KEY (option_id) 
            REFERENCES poll_options (id) 
            ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes 
            ADD CONSTRAINT FK_poll_votes_user 
            FOREIGN KEY (user_id) 
            REFERENCES user (id_user) 
            ON DELETE CASCADE
        SQL);

        // Polls table
        $this->addSql(<<<'SQL'
            ALTER TABLE polls 
            CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE polls 
            ADD CONSTRAINT FK_polls_chat 
            FOREIGN KEY (chat_id) 
            REFERENCES chat (id) 
            ON DELETE CASCADE
        SQL);

        // Projets table
        $this->addSql(<<<'SQL'
            ALTER TABLE projets 
            CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets 
            ADD CONSTRAINT FK_projets_technologie 
            FOREIGN KEY (technologie_id) 
            REFERENCES technologies (id) 
            ON DELETE CASCADE
        SQL);

        // Ressources table
        $this->addSql(<<<'SQL'
            ALTER TABLE ressources 
            CHANGE id id INT NOT NULL, 
            CHANGE description description LONGTEXT NOT NULL, 
            CHANGE date_ajout date_ajout DATETIME NOT NULL
        SQL);

        // Technologies table
        $this->addSql(<<<'SQL'
            ALTER TABLE technologies 
            CHANGE id id INT NOT NULL
        SQL);

        // User table
        $this->addSql(<<<'SQL'
            ALTER TABLE user 
            CHANGE id_user id_user INT NOT NULL, 
            CHANGE photo_user photo_user VARCHAR(255) NOT NULL
        SQL);

        // Vote table
        $this->addSql(<<<'SQL'
            ALTER TABLE vote 
            CHANGE id id INT NOT NULL, 
            CHANGE idEvaluation idEvaluation INT DEFAULT NULL, 
            CHANGE idVotant idVotant INT DEFAULT NULL, 
            CHANGE idProjet idProjet INT DEFAULT NULL, 
            CHANGE idHackathon idHackathon INT DEFAULT NULL, 
            CHANGE valeurVote valeur_vote DOUBLE PRECISION NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote 
            ADD CONSTRAINT FK_vote_evaluation 
            FOREIGN KEY (idEvaluation) 
            REFERENCES evaluation (id) 
            ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote 
            ADD CONSTRAINT FK_vote_hackathon 
            FOREIGN KEY (idHackathon) 
            REFERENCES hackathon (id_hackathon) 
            ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote 
            ADD CONSTRAINT FK_vote_projet 
            FOREIGN KEY (idProjet) 
            REFERENCES projets (id) 
            ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote 
            ADD CONSTRAINT FK_vote_votant 
            FOREIGN KEY (idVotant) 
            REFERENCES user (id_user) 
            ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // Drop messenger_messages table
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);

        // Chapitres table
        $this->addSql(<<<'SQL'
            ALTER TABLE chapitres 
            DROP FOREIGN KEY FK_chapitres_ressources
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapitres 
            CHANGE id id INT AUTO_INCREMENT NOT NULL, 
            CHANGE id_ressources id_ressources INT NOT NULL, 
            CHANGE contenu contenu TEXT NOT NULL
        SQL);

        // Chat table
        $this->addSql(<<<'SQL'
            ALTER TABLE chat 
            DROP FOREIGN KEY FK_chat_communaute
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chat 
            CHANGE id id INT AUTO_INCREMENT NOT NULL, 
            CHANGE is_active is_active TINYINT(1) DEFAULT 1 NOT NULL
        SQL);

        // Communaute table
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute 
            DROP FOREIGN KEY FK_communaute_hackathon
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute 
            CHANGE id id INT AUTO_INCREMENT NOT NULL, 
            CHANGE id_hackathon id_hackathon INT NOT NULL, 
            CHANGE is_active is_active TINYINT(1) DEFAULT 1 NOT NULL
        SQL);

        // Evaluation table
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation 
            DROP FOREIGN KEY FK_evaluation_hackathon
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation 
            DROP FOREIGN KEY FK_evaluation_projet
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation 
            DROP FOREIGN KEY FK_evaluation_jury
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation 
            ADD NoteTech DOUBLE PRECISION NOT NULL, 
            ADD NoteInnov DOUBLE PRECISION NOT NULL, 
            DROP note_tech, 
            DROP note_innov, 
            CHANGE id id INT AUTO_INCREMENT NOT NULL, 
            CHANGE idJury idJury INT NOT NULL, 
            CHANGE idHackathon idHackathon INT NOT NULL, 
            CHANGE idProjet idProjet INT NOT NULL
        SQL);

        // Hackathon table
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon 
            DROP FOREIGN KEY FK_hackathon_organisateur
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon 
            CHANGE id_hackathon id_hackathon INT AUTO_INCREMENT NOT NULL, 
            CHANGE id_organisateur id_organisateur INT NOT NULL, 
            CHANGE max_participants max_participants INT DEFAULT 1 NOT NULL
        SQL);

        // Message table
        $this->addSql(<<<'SQL'
            ALTER TABLE message 
            DROP FOREIGN KEY FK_message_chat
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message 
            DROP FOREIGN KEY FK_message_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message 
            CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);

        // Participation table
        $this->addSql(<<<'SQL'
            ALTER TABLE participation 
            DROP FOREIGN KEY FK_participation_hackathon
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation 
            DROP FOREIGN KEY FK_participation_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation 
            CHANGE id_participation id_participation INT AUTO_INCREMENT NOT NULL, 
            CHANGE id_hackathon id_hackathon INT NOT NULL, 
            CHANGE id_participant id_participant INT NOT NULL, 
            CHANGE date_inscription date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, 
            CHANGE statut statut VARCHAR(20) DEFAULT 'En attente' NOT NULL
        SQL);

        // Poll_options table
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_options 
            DROP FOREIGN KEY FK_poll_options_poll
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_options 
            CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);

        // Poll_votes table
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes 
            DROP FOREIGN KEY FK_poll_votes_poll
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes 
            DROP FOREIGN KEY FK_poll_votes_option
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes 
            DROP FOREIGN KEY FK_poll_votes_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes 
            CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);

        // Polls table
        $this->addSql(<<<'SQL'
            ALTER TABLE polls 
            DROP FOREIGN KEY FK_polls_chat
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE polls 
            CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);

        // Projets table
        $this->addSql(<<<'SQL'
            ALTER TABLE projets 
            DROP FOREIGN KEY FK_projets_technologie
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets 
            CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);

        // Ressources table
        $this->addSql(<<<'SQL'
            ALTER TABLE ressources 
            CHANGE id id INT AUTO_INCREMENT NOT NULL, 
            CHANGE description description TEXT NOT NULL, 
            CHANGE date_ajout date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
        SQL);

        // Technologies table
        $this->addSql(<<<'SQL'
            ALTER TABLE technologies 
            CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);

        // User table
        $this->addSql(<<<'SQL'
            ALTER TABLE user 
            CHANGE id_user id_user INT AUTO_INCREMENT NOT NULL, 
            CHANGE photo_user photo_user VARCHAR(255) DEFAULT NULL
        SQL);

        // Vote table
        $this->addSql(<<<'SQL'
            ALTER TABLE vote 
            DROP FOREIGN KEY FK_vote_evaluation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote 
            DROP FOREIGN KEY FK_vote_hackathon
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote 
            DROP FOREIGN KEY FK_vote_projet
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote 
            DROP FOREIGN KEY FK_vote_votant
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote 
            CHANGE id id INT AUTO_INCREMENT NOT NULL, 
            CHANGE idEvaluation idEvaluation INT NOT NULL, 
            CHANGE idVotant idVotant INT NOT NULL, 
            CHANGE idProjet idProjet INT NOT NULL, 
            CHANGE idHackathon idHackathon INT NOT NULL, 
            CHANGE valeur_vote valeurVote DOUBLE PRECISION NOT NULL
        SQL);
    }
}