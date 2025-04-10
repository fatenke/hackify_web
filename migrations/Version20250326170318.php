<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250326170318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP FOREIGN KEY idHackathon
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY idEvaluation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE hackathon
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE projet
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE vote
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD id_jury INT NOT NULL, ADD id_hackathon INT NOT NULL, ADD note_tech DOUBLE PRECISION NOT NULL, ADD note_innov DOUBLE PRECISION NOT NULL, DROP idJury, DROP idHackathon, DROP idProjet, DROP NoteTech, DROP NoteInnov
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE hackathon (idHackathon INT NOT NULL, PRIMARY KEY(idHackathon)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE projet (idProjet INT NOT NULL, idHackathon INT NOT NULL, INDEX idHackathon (idHackathon), PRIMARY KEY(idProjet)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, idEvaluation INT NOT NULL, idVotant INT NOT NULL, idProjet INT NOT NULL, idHackathon INT NOT NULL, valeurVote DOUBLE PRECISION NOT NULL, date DATE NOT NULL, INDEX idEvaluation (idEvaluation), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD CONSTRAINT idHackathon FOREIGN KEY (idHackathon) REFERENCES hackathon (idHackathon)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT idEvaluation FOREIGN KEY (idEvaluation) REFERENCES evaluation (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD idJury INT NOT NULL, ADD idHackathon INT NOT NULL, ADD idProjet INT NOT NULL, ADD NoteTech DOUBLE PRECISION NOT NULL, ADD NoteInnov DOUBLE PRECISION NOT NULL, DROP id_jury, DROP id_hackathon, DROP note_tech, DROP note_innov
        SQL);
    }
}
