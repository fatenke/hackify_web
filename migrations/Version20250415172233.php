<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250415172233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE participation CHANGE id_participant id_participant INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets ADD id_hackathon INT DEFAULT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets ADD CONSTRAINT FK_B454C1DBB0F4A68 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id_hackathon)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B454C1DBB0F4A68 ON projets (id_hackathon)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE participation CHANGE id_participant id_participant INT DEFAULT 2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets DROP FOREIGN KEY FK_B454C1DBB0F4A68
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B454C1DBB0F4A68 ON projets
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets DROP id_hackathon, CHANGE id id INT NOT NULL
        SQL);
    }
}
