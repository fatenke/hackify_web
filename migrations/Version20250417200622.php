<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417200622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_AB55E24FCF8DA6E6 ON participation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation CHANGE id_participant id_participant INT NOT NULL
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
            ALTER TABLE participation CHANGE id_participant id_participant INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AB55E24FCF8DA6E6 ON participation (id_participant)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE polls DROP FOREIGN KEY FK_1D3CC6EE1A9A7125
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
    }
}
