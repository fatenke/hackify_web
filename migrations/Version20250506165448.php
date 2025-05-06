<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506165448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute ADD CONSTRAINT FK_21C94799B0F4A68 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id) ON DELETE CASCADE
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
            ALTER TABLE evaluation ADD noteTech DOUBLE PRECISION NOT NULL, ADD noteInnov DOUBLE PRECISION NOT NULL, DROP note_tech, DROP note_innov, CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57560C8EEB2 FOREIGN KEY (idJury) REFERENCES jury (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57577D0DD19 FOREIGN KEY (idHackathon) REFERENCES hackathon (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57533043433 FOREIGN KEY (idProjet) REFERENCES projet (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon MODIFY id_hackathon INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon DROP FOREIGN KEY FK_8B3AF64F68161836
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON hackathon
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon CHANGE id_hackathon id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon ADD CONSTRAINT FK_8B3AF64F68161836 FOREIGN KEY (id_organisateur) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon ADD PRIMARY KEY (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FAE36D154
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FAE36D154 FOREIGN KEY (posted_by) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FB0F4A68
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation CHANGE id_participant id_participant INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FCF8DA6E6 FOREIGN KEY (id_participant) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FB0F4A68 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AB55E24FCF8DA6E6 ON participation (id_participant)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_vote ADD CONSTRAINT FK_ED568EBEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets DROP FOREIGN KEY projets_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets DROP FOREIGN KEY projets_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets CHANGE id_hackathon id_hackathon INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets ADD CONSTRAINT FK_B454C1DBB0F4A68 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk ON projets
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B454C1DBB0F4A68 ON projets (id_hackathon)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets ADD CONSTRAINT projets_ibfk_1 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id_hackathon) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748A6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE technologies CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD id INT AUTO_INCREMENT NOT NULL, ADD prenom_user VARCHAR(255) NOT NULL, DROP id_user, CHANGE role_user role_user JSON NOT NULL COMMENT '(DC2Type:json)', CHANGE photo_user photo_user VARCHAR(255) DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D64912A5F6CC ON user (email_user)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_5A10856433043433
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_5A10856477D0DD19
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_5A108564159462A9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_5A10856491D7BB9F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE valeur_vote valeurVote DOUBLE PRECISION NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A10856433043433 FOREIGN KEY (idProjet) REFERENCES projet (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A10856477D0DD19 FOREIGN KEY (idHackathon) REFERENCES hackathon (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A108564159462A9 FOREIGN KEY (idVotant) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A10856491D7BB9F FOREIGN KEY (idEvaluation) REFERENCES evaluation (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
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
            ALTER TABLE evaluation ADD note_tech DOUBLE PRECISION NOT NULL, ADD note_innov DOUBLE PRECISION NOT NULL, DROP noteTech, DROP noteInnov, CHANGE id id INT NOT NULL
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
            ALTER TABLE hackathon MODIFY id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon DROP FOREIGN KEY FK_8B3AF64F68161836
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON hackathon
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon CHANGE id id_hackathon INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon ADD CONSTRAINT FK_8B3AF64F68161836 FOREIGN KEY (id_organisateur) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon ADD PRIMARY KEY (id_hackathon)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FAE36D154
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FAE36D154 FOREIGN KEY (posted_by) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FCF8DA6E6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FB0F4A68
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_AB55E24FCF8DA6E6 ON participation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation CHANGE id_participant id_participant INT DEFAULT 2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FB0F4A68 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id_hackathon)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_vote DROP FOREIGN KEY FK_ED568EBEA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets DROP FOREIGN KEY FK_B454C1DBB0F4A68
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets DROP FOREIGN KEY FK_B454C1DBB0F4A68
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets CHANGE id_hackathon id_hackathon INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets ADD CONSTRAINT projets_ibfk_1 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id_hackathon) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_b454c1dbb0f4a68 ON projets
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk ON projets (id_hackathon)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets ADD CONSTRAINT FK_B454C1DBB0F4A68 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748A6B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE technologies CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user MODIFY id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D64912A5F6CC ON user
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD id_user INT NOT NULL, DROP id, DROP prenom_user, CHANGE role_user role_user VARCHAR(255) NOT NULL, CHANGE photo_user photo_user VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD PRIMARY KEY (id_user)
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
            ALTER TABLE vote CHANGE id id INT NOT NULL, CHANGE valeurVote valeur_vote DOUBLE PRECISION NOT NULL
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
}
