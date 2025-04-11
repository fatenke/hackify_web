<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410234303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAC903E5B8
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX communaute_id ON chat
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_659DF2AAC903E5B8 ON chat (communaute_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAC903E5B8 FOREIGN KEY (communaute_id) REFERENCES communaute (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute CHANGE id id INT NOT NULL, CHANGE description description LONGTEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon CHANGE id_hackathon id_hackathon INT NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE conditions_participation conditions_participation LONGTEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY message_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY message_ibfk_2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message CHANGE id id INT NOT NULL, CHANGE chat_id chat_id INT DEFAULT NULL, CHANGE posted_by posted_by INT DEFAULT NULL, CHANGE contenu contenu LONGTEXT NOT NULL, CHANGE post_time post_time DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX chat_id ON message
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307F1A9A7125 ON message (chat_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX posted_by ON message
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307FAE36D154 ON message (posted_by)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT message_ibfk_1 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT message_ibfk_2 FOREIGN KEY (posted_by) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll DROP FOREIGN KEY FK_84BCFA451A9A7125
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll CHANGE id id INT NOT NULL, CHANGE chat_id chat_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll ADD CONSTRAINT FK_84BCFA451A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_option DROP FOREIGN KEY FK_B68343EB3C947C0F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_option CHANGE id id INT NOT NULL, CHANGE poll_id poll_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_option ADD CONSTRAINT FK_B68343EB3C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX mail ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_5A108564A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_5A108564A7C41D6F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_5A1085643C947C0F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote CHANGE id id INT NOT NULL, CHANGE poll_id poll_id INT DEFAULT NULL, CHANGE option_id option_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A108564A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A108564A7C41D6F FOREIGN KEY (option_id) REFERENCES poll_option (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A1085643C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAC903E5B8
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_659df2aac903e5b8 ON chat
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX communaute_id ON chat (communaute_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAC903E5B8 FOREIGN KEY (communaute_id) REFERENCES communaute (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon CHANGE id_hackathon id_hackathon INT AUTO_INCREMENT NOT NULL, CHANGE description description TEXT NOT NULL, CHANGE conditions_participation conditions_participation TEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1A9A7125
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FAE36D154
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE chat_id chat_id INT NOT NULL, CHANGE posted_by posted_by INT NOT NULL, CHANGE contenu contenu TEXT NOT NULL, CHANGE post_time post_time DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_b6bd307fae36d154 ON message
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX posted_by ON message (posted_by)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_b6bd307f1a9a7125 ON message
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX chat_id ON message (chat_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FAE36D154 FOREIGN KEY (posted_by) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll DROP FOREIGN KEY FK_84BCFA451A9A7125
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE chat_id chat_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll ADD CONSTRAINT FK_84BCFA451A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_option DROP FOREIGN KEY FK_B68343EB3C947C0F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_option CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE poll_id poll_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_option ADD CONSTRAINT FK_B68343EB3C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX mail ON user (email)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_5A1085643C947C0F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_5A108564A7C41D6F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_5A108564A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE poll_id poll_id INT NOT NULL, CHANGE option_id option_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A1085643C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A108564A7C41D6F FOREIGN KEY (option_id) REFERENCES poll_option (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_5A108564A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
    }
}
