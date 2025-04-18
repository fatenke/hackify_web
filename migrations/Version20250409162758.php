<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409162758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE chat CHANGE type type enum('ANNOUNCEMENT','QUESTION','FEEDBACK','COACH','CUSTOM','BOT_SUPPORT')
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute CHANGE id_hackathon id_hackathon INT NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE date_creation date_creation DATETIME NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE chat CHANGE type type VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute CHANGE id_hackathon id_hackathon INT DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE date_creation date_creation DATETIME DEFAULT NULL
        SQL);
    }
}
