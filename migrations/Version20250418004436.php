<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250418004436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, chat_id INT DEFAULT NULL, INDEX IDX_D79F6B11A76ED395 (user_id), INDEX IDX_D79F6B111A9A7125 (chat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE poll_option (id INT AUTO_INCREMENT NOT NULL, poll_id INT NOT NULL, text VARCHAR(255) NOT NULL, vote_count INT NOT NULL, INDEX IDX_B68343EB3C947C0F (poll_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant ADD CONSTRAINT FK_D79F6B111A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_option ADD CONSTRAINT FK_B68343EB3C947C0F FOREIGN KEY (poll_id) REFERENCES polls (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chat CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon CHANGE id_hackathon id_hackathon INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE polls DROP FOREIGN KEY FK_1D3CC6EE1A9A7125
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX chat_id ON polls
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1D3CC6EE1A9A7125 ON polls (chat_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE polls ADD CONSTRAINT FK_1D3CC6EE1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX mail ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE id id INT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B111A9A7125
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_option DROP FOREIGN KEY FK_B68343EB3C947C0F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE participant
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE poll_option
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chat CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon CHANGE id_hackathon id_hackathon INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE polls DROP FOREIGN KEY FK_1D3CC6EE1A9A7125
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_1d3cc6ee1a9a7125 ON polls
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX chat_id ON polls (chat_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE polls ADD CONSTRAINT FK_1D3CC6EE1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX mail ON user (email)
        SQL);
    }
}
