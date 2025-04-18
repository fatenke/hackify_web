<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250418054638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE poll ADD created_by INT DEFAULT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll ADD CONSTRAINT FK_84BCFA45DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_84BCFA45DE12AB56 ON poll (created_by)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE poll DROP FOREIGN KEY FK_84BCFA45DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_84BCFA45DE12AB56 ON poll
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll DROP created_by, CHANGE id id INT NOT NULL
        SQL);
    }
}
