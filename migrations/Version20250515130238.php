<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250515130238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57560C8EEB2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE jury
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapitres CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57560C8EEB2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57560C8EEB2 FOREIGN KEY (idJury) REFERENCES user (id)
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
            ALTER TABLE projets ADD CONSTRAINT FK_B454C1DBB0F4A68 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id_hackathon) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B454C1DBB0F4A68 ON projets (id_hackathon)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ressources CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE jury (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapitres CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57560C8EEB2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57560C8EEB2 FOREIGN KEY (idJury) REFERENCES jury (id)
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
            ALTER TABLE projets ADD CONSTRAINT FK_B454C1DB996D90CF FOREIGN KEY (hackathon_id) REFERENCES hackathon (id_hackathon)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B454C1DB996D90CF ON projets (hackathon_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ressources CHANGE id id INT NOT NULL
        SQL);
    }
}
