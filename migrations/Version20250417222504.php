<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417222504 extends AbstractMigration
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
            CREATE TABLE chat (id INT NOT NULL, communaute_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_659DF2AAC903E5B8 (communaute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE communaute (id INT NOT NULL, id_hackathon INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_21C94799B0F4A68 (id_hackathon), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE evaluation (id INT NOT NULL, note_tech DOUBLE PRECISION NOT NULL, note_innov DOUBLE PRECISION NOT NULL, date DATE NOT NULL, idJury INT DEFAULT NULL, idHackathon INT DEFAULT NULL, idProjet INT DEFAULT NULL, INDEX IDX_1323A57560C8EEB2 (idJury), INDEX IDX_1323A57577D0DD19 (idHackathon), INDEX IDX_1323A57533043433 (idProjet), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE hackathon (id_hackathon INT AUTO_INCREMENT NOT NULL, id_organisateur INT DEFAULT NULL, nom_hackathon VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, lieu VARCHAR(255) NOT NULL, theme VARCHAR(255) NOT NULL, max_participants INT NOT NULL, INDEX IDX_8B3AF64F68161836 (id_organisateur), PRIMARY KEY(id_hackathon)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE message (id INT NOT NULL, chat_id INT DEFAULT NULL, posted_by INT DEFAULT NULL, contenu LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, post_time DATETIME NOT NULL, INDEX IDX_B6BD307F1A9A7125 (chat_id), INDEX IDX_B6BD307FAE36D154 (posted_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE participation (id_participation INT AUTO_INCREMENT NOT NULL, id_hackathon INT DEFAULT NULL, id_participant INT DEFAULT NULL, date_inscription DATETIME NOT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_AB55E24FB0F4A68 (id_hackathon), INDEX IDX_AB55E24FCF8DA6E6 (id_participant), PRIMARY KEY(id_participation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE poll_options (id INT NOT NULL, poll_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, vote_count INT NOT NULL, INDEX IDX_2C6077B83C947C0F (poll_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE poll_votes (id INT NOT NULL, poll_id INT DEFAULT NULL, option_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_373A070E3C947C0F (poll_id), INDEX IDX_373A070EA7C41D6F (option_id), INDEX IDX_373A070EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE polls (id INT NOT NULL, chat_id INT DEFAULT NULL, question VARCHAR(255) NOT NULL, is_closed TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_1D3CC6EE1A9A7125 (chat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE projets (id INT AUTO_INCREMENT NOT NULL, id_hackathon INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, priorite VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, ressource VARCHAR(255) NOT NULL, INDEX IDX_B454C1DBB0F4A68 (id_hackathon), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE projets_technologies (projets_id INT NOT NULL, technologies_id INT NOT NULL, INDEX IDX_DCC225EE597A6CB7 (projets_id), INDEX IDX_DCC225EE8F8A14FA (technologies_id), PRIMARY KEY(projets_id, technologies_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ressources (id INT NOT NULL, titre VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date_ajout DATETIME NOT NULL, valide TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE technologies (id INT NOT NULL, nom_tech VARCHAR(255) NOT NULL, type_tech VARCHAR(255) NOT NULL, complexite VARCHAR(255) NOT NULL, documentaire VARCHAR(255) NOT NULL, compatibilite VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id_user INT AUTO_INCREMENT NOT NULL, nom_user VARCHAR(255) NOT NULL, email_user VARCHAR(255) NOT NULL, role_user JSON NOT NULL COMMENT '(DC2Type:json)', tel_user INT NOT NULL, mdp_user VARCHAR(255) NOT NULL, adresse_user VARCHAR(255) NOT NULL, photo_user VARCHAR(255) DEFAULT NULL, status_user VARCHAR(20) NOT NULL, prenom_user VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D64912A5F6CC (email_user), PRIMARY KEY(id_user)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE vote (id INT NOT NULL, valeur_vote DOUBLE PRECISION NOT NULL, date DATE NOT NULL, idEvaluation INT DEFAULT NULL, idVotant INT DEFAULT NULL, idProjet INT DEFAULT NULL, idHackathon INT DEFAULT NULL, INDEX IDX_5A10856491D7BB9F (idEvaluation), INDEX IDX_5A108564159462A9 (idVotant), INDEX IDX_5A10856433043433 (idProjet), INDEX IDX_5A10856477D0DD19 (idHackathon), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
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
            ALTER TABLE communaute ADD CONSTRAINT FK_21C94799B0F4A68 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id_hackathon) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57560C8EEB2 FOREIGN KEY (idJury) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57577D0DD19 FOREIGN KEY (idHackathon) REFERENCES hackathon (id_hackathon) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57533043433 FOREIGN KEY (idProjet) REFERENCES projets (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon ADD CONSTRAINT FK_8B3AF64F68161836 FOREIGN KEY (id_organisateur) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FAE36D154 FOREIGN KEY (posted_by) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FB0F4A68 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id_hackathon)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FCF8DA6E6 FOREIGN KEY (id_participant) REFERENCES user (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_options ADD CONSTRAINT FK_2C6077B83C947C0F FOREIGN KEY (poll_id) REFERENCES polls (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes ADD CONSTRAINT FK_373A070E3C947C0F FOREIGN KEY (poll_id) REFERENCES polls (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes ADD CONSTRAINT FK_373A070EA7C41D6F FOREIGN KEY (option_id) REFERENCES poll_options (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes ADD CONSTRAINT FK_373A070EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE polls ADD CONSTRAINT FK_1D3CC6EE1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets ADD CONSTRAINT FK_B454C1DBB0F4A68 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id_hackathon)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets_technologies ADD CONSTRAINT FK_DCC225EE597A6CB7 FOREIGN KEY (projets_id) REFERENCES projets (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets_technologies ADD CONSTRAINT FK_DCC225EE8F8A14FA FOREIGN KEY (technologies_id) REFERENCES technologies (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A10856491D7BB9F FOREIGN KEY (idEvaluation) REFERENCES evaluation (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A108564159462A9 FOREIGN KEY (idVotant) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A10856433043433 FOREIGN KEY (idProjet) REFERENCES projets (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A10856477D0DD19 FOREIGN KEY (idHackathon) REFERENCES hackathon (id_hackathon) ON DELETE CASCADE
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
            ALTER TABLE poll_options DROP FOREIGN KEY FK_2C6077B83C947C0F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes DROP FOREIGN KEY FK_373A070E3C947C0F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes DROP FOREIGN KEY FK_373A070EA7C41D6F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes DROP FOREIGN KEY FK_373A070EA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE polls DROP FOREIGN KEY FK_1D3CC6EE1A9A7125
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets DROP FOREIGN KEY FK_B454C1DBB0F4A68
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets_technologies DROP FOREIGN KEY FK_DCC225EE597A6CB7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets_technologies DROP FOREIGN KEY FK_DCC225EE8F8A14FA
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
            DROP TABLE message
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE participation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE poll_options
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE poll_votes
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE polls
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE projets
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE projets_technologies
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
