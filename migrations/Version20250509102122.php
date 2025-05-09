<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250509102122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE reaction (id INT AUTO_INCREMENT NOT NULL, message_id INT DEFAULT NULL, user_id INT DEFAULT NULL, emoji VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_A4D707F7537A1329 (message_id), INDEX IDX_A4D707F7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reaction ADD CONSTRAINT FK_A4D707F7537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reaction ADD CONSTRAINT FK_A4D707F7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE projet
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapitres CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57577D0DD19
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57533043433
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57577D0DD19 FOREIGN KEY (idHackathon) REFERENCES hackathon (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57533043433 FOREIGN KEY (idProjet) REFERENCES projets (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FCF8DA6E6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FCF8DA6E6 FOREIGN KEY (id_participant) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_vote DROP FOREIGN KEY FK_ED568EBEA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_ED568EBEA76ED395 ON poll_vote
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_vote CHANGE user_id user INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_vote ADD CONSTRAINT FK_ED568EBE8D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_ED568EBE8D93D649 ON poll_vote (user)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets DROP FOREIGN KEY FK_B454C1DBB0F4A68
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B454C1DBB0F4A68 ON projets
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets CHANGE id_hackathon hackathon_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets ADD CONSTRAINT FK_B454C1DB996D90CF FOREIGN KEY (hackathon_id) REFERENCES hackathon (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B454C1DB996D90CF ON projets (hackathon_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ressources CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE technologies CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, idHackathon INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reaction DROP FOREIGN KEY FK_A4D707F7537A1329
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reaction DROP FOREIGN KEY FK_A4D707F7A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reaction
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapitres CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57577D0DD19
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57533043433
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57577D0DD19 FOREIGN KEY (idHackathon) REFERENCES hackathon (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57533043433 FOREIGN KEY (idProjet) REFERENCES projets (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FCF8DA6E6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FCF8DA6E6 FOREIGN KEY (id_participant) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_vote DROP FOREIGN KEY FK_ED568EBE8D93D649
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_ED568EBE8D93D649 ON poll_vote
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_vote CHANGE user user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_vote ADD CONSTRAINT FK_ED568EBEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_ED568EBEA76ED395 ON poll_vote (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets DROP FOREIGN KEY FK_B454C1DB996D90CF
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B454C1DB996D90CF ON projets
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets CHANGE hackathon_id id_hackathon INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets ADD CONSTRAINT FK_B454C1DBB0F4A68 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B454C1DBB0F4A68 ON projets (id_hackathon)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ressources CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE technologies CHANGE id id INT NOT NULL
        SQL);
    }
}
