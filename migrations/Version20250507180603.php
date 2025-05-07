<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507180603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE chapitres (id INT NOT NULL, id_ressources INT DEFAULT NULL, url_fichier VARCHAR(500) NOT NULL, titre VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, format_fichier VARCHAR(50) NOT NULL, INDEX IDX_508679FCCFA65510 (id_ressources), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, communaute_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_659DF2AAC903E5B8 (communaute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE communaute (id INT AUTO_INCREMENT NOT NULL, id_hackathon INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_21C94799B0F4A68 (id_hackathon), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, noteTech DOUBLE PRECISION NOT NULL, noteInnov DOUBLE PRECISION NOT NULL, date DATE NOT NULL, idJury INT DEFAULT NULL, idHackathon INT DEFAULT NULL, idProjet INT DEFAULT NULL, INDEX IDX_1323A57560C8EEB2 (idJury), INDEX IDX_1323A57577D0DD19 (idHackathon), INDEX IDX_1323A57533043433 (idProjet), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE hackathon (id INT AUTO_INCREMENT NOT NULL, id_organisateur INT DEFAULT NULL, nom_hackathon VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, lieu VARCHAR(255) NOT NULL, theme VARCHAR(255) NOT NULL, max_participants INT NOT NULL, INDEX IDX_8B3AF64F68161836 (id_organisateur), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE jury (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, chat_id INT DEFAULT NULL, posted_by INT DEFAULT NULL, contenu LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, post_time DATETIME NOT NULL, INDEX IDX_B6BD307F1A9A7125 (chat_id), INDEX IDX_B6BD307FAE36D154 (posted_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE participation (id_participation INT AUTO_INCREMENT NOT NULL, id_hackathon INT DEFAULT NULL, id_participant INT DEFAULT NULL, date_inscription DATETIME NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_AB55E24FB0F4A68 (id_hackathon), INDEX IDX_AB55E24FCF8DA6E6 (id_participant), PRIMARY KEY(id_participation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE poll (id INT AUTO_INCREMENT NOT NULL, chat_id INT DEFAULT NULL, question VARCHAR(255) NOT NULL, is_closed TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_84BCFA451A9A7125 (chat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE poll_option (id INT AUTO_INCREMENT NOT NULL, poll_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, vote_count INT NOT NULL, INDEX IDX_B68343EB3C947C0F (poll_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE poll_vote (id INT AUTO_INCREMENT NOT NULL, poll_id INT DEFAULT NULL, option_id INT DEFAULT NULL, user INT DEFAULT NULL, INDEX IDX_ED568EBE3C947C0F (poll_id), INDEX IDX_ED568EBEA7C41D6F (option_id), INDEX IDX_ED568EBE8D93D649 (user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE projets (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, priorite VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, ressource VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE projets_technologies (projets_id INT NOT NULL, technologies_id INT NOT NULL, INDEX IDX_DCC225EE597A6CB7 (projets_id), INDEX IDX_DCC225EE8F8A14FA (technologies_id), PRIMARY KEY(projets_id, technologies_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reaction (id INT AUTO_INCREMENT NOT NULL, message_id INT DEFAULT NULL, user_id INT DEFAULT NULL, emoji VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_A4D707F7537A1329 (message_id), INDEX IDX_A4D707F7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', expires_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_7CE748A6B3CA4B (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ressources (id INT NOT NULL, titre VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_ajout DATETIME NOT NULL, valide TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE technologies (id INT AUTO_INCREMENT NOT NULL, nom_tech VARCHAR(255) NOT NULL, type_tech VARCHAR(255) NOT NULL, complexite VARCHAR(255) NOT NULL, documentaire VARCHAR(255) NOT NULL, compatibilite VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom_user VARCHAR(255) NOT NULL, email_user VARCHAR(255) NOT NULL, role_user JSON NOT NULL COMMENT '(DC2Type:json)', tel_user INT NOT NULL, mdp_user VARCHAR(255) NOT NULL, adresse_user VARCHAR(255) NOT NULL, photo_user VARCHAR(255) DEFAULT NULL, status_user VARCHAR(20) NOT NULL, prenom_user VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D64912A5F6CC (email_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, valeurVote DOUBLE PRECISION NOT NULL, date DATE NOT NULL, idEvaluation INT DEFAULT NULL, idVotant INT DEFAULT NULL, idProjet INT DEFAULT NULL, idHackathon INT DEFAULT NULL, INDEX IDX_5A10856491D7BB9F (idEvaluation), INDEX IDX_5A108564159462A9 (idVotant), INDEX IDX_5A10856433043433 (idProjet), INDEX IDX_5A10856477D0DD19 (idHackathon), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapitres ADD CONSTRAINT FK_508679FCCFA65510 FOREIGN KEY (id_ressources) REFERENCES ressources (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAC903E5B8 FOREIGN KEY (communaute_id) REFERENCES communaute (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute ADD CONSTRAINT FK_21C94799B0F4A68 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57560C8EEB2 FOREIGN KEY (idJury) REFERENCES jury (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57577D0DD19 FOREIGN KEY (idHackathon) REFERENCES hackathon (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57533043433 FOREIGN KEY (idProjet) REFERENCES projets (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon ADD CONSTRAINT FK_8B3AF64F68161836 FOREIGN KEY (id_organisateur) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FAE36D154 FOREIGN KEY (posted_by) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FB0F4A68 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FCF8DA6E6 FOREIGN KEY (id_participant) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll ADD CONSTRAINT FK_84BCFA451A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_option ADD CONSTRAINT FK_B68343EB3C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_vote ADD CONSTRAINT FK_ED568EBE3C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_vote ADD CONSTRAINT FK_ED568EBEA7C41D6F FOREIGN KEY (option_id) REFERENCES poll_option (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_vote ADD CONSTRAINT FK_ED568EBE8D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets ADD CONSTRAINT FK_B454C1DBBF396750 FOREIGN KEY (id) REFERENCES hackathon (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets_technologies ADD CONSTRAINT FK_DCC225EE597A6CB7 FOREIGN KEY (projets_id) REFERENCES projets (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets_technologies ADD CONSTRAINT FK_DCC225EE8F8A14FA FOREIGN KEY (technologies_id) REFERENCES technologies (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reaction ADD CONSTRAINT FK_A4D707F7537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reaction ADD CONSTRAINT FK_A4D707F7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748A6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A10856491D7BB9F FOREIGN KEY (idEvaluation) REFERENCES evaluation (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A108564159462A9 FOREIGN KEY (idVotant) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A10856433043433 FOREIGN KEY (idProjet) REFERENCES projets (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A10856477D0DD19 FOREIGN KEY (idHackathon) REFERENCES hackathon (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE chapitres DROP FOREIGN KEY FK_508679FCCFA65510
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAC903E5B8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute DROP FOREIGN KEY FK_21C94799B0F4A68
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57560C8EEB2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57577D0DD19
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57533043433
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon DROP FOREIGN KEY FK_8B3AF64F68161836
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1A9A7125
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FAE36D154
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FB0F4A68
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FCF8DA6E6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll DROP FOREIGN KEY FK_84BCFA451A9A7125
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_option DROP FOREIGN KEY FK_B68343EB3C947C0F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_vote DROP FOREIGN KEY FK_ED568EBE3C947C0F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_vote DROP FOREIGN KEY FK_ED568EBEA7C41D6F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_vote DROP FOREIGN KEY FK_ED568EBE8D93D649
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets DROP FOREIGN KEY FK_B454C1DBBF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets_technologies DROP FOREIGN KEY FK_DCC225EE597A6CB7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets_technologies DROP FOREIGN KEY FK_DCC225EE8F8A14FA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reaction DROP FOREIGN KEY FK_A4D707F7537A1329
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reaction DROP FOREIGN KEY FK_A4D707F7A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748A6B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_5A10856491D7BB9F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_5A108564159462A9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_5A10856433043433
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_5A10856477D0DD19
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE chapitres
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE chat
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE communaute
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE evaluation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE hackathon
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE jury
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE message
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE participation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE poll
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE poll_option
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE poll_vote
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE projets
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE projets_technologies
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reaction
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reset_password_request
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ressources
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE technologies
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE vote
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
