<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250414210051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE chapitres DROP FOREIGN KEY FK_chapitres_ressources
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX id_ressources ON chapitres
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_508679FCCFA65510 ON chapitres (id_ressources)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapitres ADD CONSTRAINT FK_chapitres_ressources FOREIGN KEY (id_ressources) REFERENCES ressources (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chat DROP FOREIGN KEY FK_chat_communaute
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chat DROP FOREIGN KEY chat_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chat DROP FOREIGN KEY FK_chat_communaute
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX communaute_id ON chat
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_659DF2AAC903E5B8 ON chat (communaute_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chat ADD CONSTRAINT chat_ibfk_1 FOREIGN KEY (communaute_id) REFERENCES communaute (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chat ADD CONSTRAINT FK_chat_communaute FOREIGN KEY (communaute_id) REFERENCES communaute (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute DROP FOREIGN KEY communaute_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute DROP FOREIGN KEY FK_communaute_hackathon
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute DROP FOREIGN KEY communaute_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX id_hackathon ON communaute
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_21C94799B0F4A68 ON communaute (id_hackathon)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute ADD CONSTRAINT FK_communaute_hackathon FOREIGN KEY (id_hackathon) REFERENCES hackathon (id_hackathon) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute ADD CONSTRAINT communaute_ibfk_1 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id_hackathon) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY evaluation_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY FK_evaluation_projet
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY evaluation_ibfk_3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY FK_evaluation_hackathon
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY evaluation_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY FK_evaluation_jury
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY evaluation_ibfk_2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY FK_evaluation_projet
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation DROP FOREIGN KEY evaluation_ibfk_3
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_evaluation_jury ON evaluation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1323A57560C8EEB2 ON evaluation (idJury)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idhackathon ON evaluation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1323A57577D0DD19 ON evaluation (idHackathon)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idprojet ON evaluation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1323A57533043433 ON evaluation (idProjet)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_evaluation_hackathon FOREIGN KEY (idHackathon) REFERENCES hackathon (id_hackathon) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT evaluation_ibfk_1 FOREIGN KEY (idHackathon) REFERENCES hackathon (id_hackathon) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_evaluation_jury FOREIGN KEY (idJury) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT evaluation_ibfk_2 FOREIGN KEY (idProjet) REFERENCES projets (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT FK_evaluation_projet FOREIGN KEY (idProjet) REFERENCES projets (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE evaluation ADD CONSTRAINT evaluation_ibfk_3 FOREIGN KEY (idJury) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon DROP FOREIGN KEY hackathon_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon DROP FOREIGN KEY FK_hackathon_organisateur
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon DROP FOREIGN KEY hackathon_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX id_organisateur ON hackathon
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8B3AF64F68161836 ON hackathon (id_organisateur)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon ADD CONSTRAINT FK_hackathon_organisateur FOREIGN KEY (id_organisateur) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon ADD CONSTRAINT hackathon_ibfk_1 FOREIGN KEY (id_organisateur) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_message_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY message_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_message_chat
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY message_ibfk_2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_message_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY message_ibfk_1
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
            ALTER TABLE message ADD CONSTRAINT FK_message_chat FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT message_ibfk_2 FOREIGN KEY (posted_by) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_message_user FOREIGN KEY (posted_by) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT message_ibfk_1 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_participation_hackathon
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_participation_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY participation_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_participation_hackathon
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY participation_ibfk_2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_participation_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX id_hackathon ON participation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AB55E24FB0F4A68 ON participation (id_hackathon)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX id_participant ON participation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AB55E24FCF8DA6E6 ON participation (id_participant)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT participation_ibfk_1 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id_hackathon) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_participation_hackathon FOREIGN KEY (id_hackathon) REFERENCES hackathon (id_hackathon) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT participation_ibfk_2 FOREIGN KEY (id_participant) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_participation_user FOREIGN KEY (id_participant) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_options DROP FOREIGN KEY poll_options_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_options DROP FOREIGN KEY FK_poll_options_poll
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_options DROP FOREIGN KEY poll_options_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX poll_id ON poll_options
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2C6077B83C947C0F ON poll_options (poll_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_options ADD CONSTRAINT FK_poll_options_poll FOREIGN KEY (poll_id) REFERENCES polls (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_options ADD CONSTRAINT poll_options_ibfk_1 FOREIGN KEY (poll_id) REFERENCES polls (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes DROP FOREIGN KEY FK_poll_votes_poll
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes DROP FOREIGN KEY poll_votes_ibfk_2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes DROP FOREIGN KEY poll_votes_ibfk_3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes DROP FOREIGN KEY FK_poll_votes_option
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes DROP FOREIGN KEY poll_votes_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes DROP FOREIGN KEY FK_poll_votes_poll
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes DROP FOREIGN KEY poll_votes_ibfk_2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes DROP FOREIGN KEY FK_poll_votes_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes DROP FOREIGN KEY poll_votes_ibfk_3
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX poll_id ON poll_votes
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_373A070E3C947C0F ON poll_votes (poll_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX option_id ON poll_votes
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_373A070EA7C41D6F ON poll_votes (option_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX user_id ON poll_votes
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_373A070EA76ED395 ON poll_votes (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes ADD CONSTRAINT FK_poll_votes_option FOREIGN KEY (option_id) REFERENCES poll_options (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes ADD CONSTRAINT poll_votes_ibfk_1 FOREIGN KEY (poll_id) REFERENCES polls (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes ADD CONSTRAINT FK_poll_votes_poll FOREIGN KEY (poll_id) REFERENCES polls (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes ADD CONSTRAINT poll_votes_ibfk_2 FOREIGN KEY (option_id) REFERENCES poll_options (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes ADD CONSTRAINT FK_poll_votes_user FOREIGN KEY (user_id) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_votes ADD CONSTRAINT poll_votes_ibfk_3 FOREIGN KEY (user_id) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE polls DROP FOREIGN KEY FK_polls_chat
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE polls DROP FOREIGN KEY polls_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE polls DROP FOREIGN KEY FK_polls_chat
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX chat_id ON polls
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1D3CC6EE1A9A7125 ON polls (chat_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE polls ADD CONSTRAINT polls_ibfk_1 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE polls ADD CONSTRAINT FK_polls_chat FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets DROP FOREIGN KEY projets_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets DROP FOREIGN KEY FK_projets_technologie
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets DROP FOREIGN KEY projets_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX technologie_id ON projets
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B454C1DB261A27D2 ON projets (technologie_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets ADD CONSTRAINT FK_projets_technologie FOREIGN KEY (technologie_id) REFERENCES technologies (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets ADD CONSTRAINT projets_ibfk_1 FOREIGN KEY (technologie_id) REFERENCES technologies (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY vote_ibfk_2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_vote_evaluation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_vote_votant
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY vote_ibfk_3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_vote_hackathon
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY vote_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY vote_ibfk_4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_vote_projet
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY vote_ibfk_2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_vote_evaluation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY FK_vote_votant
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote DROP FOREIGN KEY vote_ibfk_3
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idevaluation ON vote
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5A10856491D7BB9F ON vote (idEvaluation)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_vote_votant ON vote
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5A108564159462A9 ON vote (idVotant)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idprojet ON vote
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5A10856433043433 ON vote (idProjet)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idhackathon ON vote
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5A10856477D0DD19 ON vote (idHackathon)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_vote_hackathon FOREIGN KEY (idHackathon) REFERENCES hackathon (id_hackathon) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT vote_ibfk_1 FOREIGN KEY (idEvaluation) REFERENCES evaluation (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT vote_ibfk_4 FOREIGN KEY (idVotant) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_vote_projet FOREIGN KEY (idProjet) REFERENCES projets (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT vote_ibfk_2 FOREIGN KEY (idHackathon) REFERENCES hackathon (id_hackathon) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_vote_evaluation FOREIGN KEY (idEvaluation) REFERENCES evaluation (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT FK_vote_votant FOREIGN KEY (idVotant) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vote ADD CONSTRAINT vote_ibfk_3 FOREIGN KEY (idProjet) REFERENCES projets (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE chapitres DROP FOREIGN KEY FK_508679FCCFA65510
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_508679fccfa65510 ON chapitres
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX id_ressources ON chapitres (id_ressources)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapitres ADD CONSTRAINT FK_508679FCCFA65510 FOREIGN KEY (id_ressources) REFERENCES ressources (id) ON DELETE CASCADE
        SQL);
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
            ALTER TABLE communaute DROP FOREIGN KEY FK_21C94799B0F4A68
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_21c94799b0f4a68 ON communaute
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX id_hackathon ON communaute (id_hackathon)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE communaute ADD CONSTRAINT FK_21C94799B0F4A68 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id_hackathon) ON DELETE CASCADE
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
            DROP INDEX idx_1323a57560c8eeb2 ON evaluation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX FK_evaluation_jury ON evaluation (idJury)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_1323a57577d0dd19 ON evaluation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idHackathon ON evaluation (idHackathon)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_1323a57533043433 ON evaluation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idProjet ON evaluation (idProjet)
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
            ALTER TABLE hackathon DROP FOREIGN KEY FK_8B3AF64F68161836
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_8b3af64f68161836 ON hackathon
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX id_organisateur ON hackathon (id_organisateur)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hackathon ADD CONSTRAINT FK_8B3AF64F68161836 FOREIGN KEY (id_organisateur) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1A9A7125
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FAE36D154
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_b6bd307f1a9a7125 ON message
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX chat_id ON message (chat_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_b6bd307fae36d154 ON message
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX posted_by ON message (posted_by)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FAE36D154 FOREIGN KEY (posted_by) REFERENCES user (id_user) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FB0F4A68
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FCF8DA6E6
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_ab55e24fb0f4a68 ON participation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX id_hackathon ON participation (id_hackathon)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_ab55e24fcf8da6e6 ON participation
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX id_participant ON participation (id_participant)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FB0F4A68 FOREIGN KEY (id_hackathon) REFERENCES hackathon (id_hackathon) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FCF8DA6E6 FOREIGN KEY (id_participant) REFERENCES user (id_user) ON DELETE CASCADE
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
            ALTER TABLE poll_options DROP FOREIGN KEY FK_2C6077B83C947C0F
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_2c6077b83c947c0f ON poll_options
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX poll_id ON poll_options (poll_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poll_options ADD CONSTRAINT FK_2C6077B83C947C0F FOREIGN KEY (poll_id) REFERENCES polls (id) ON DELETE CASCADE
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
            DROP INDEX idx_373a070ea76ed395 ON poll_votes
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX user_id ON poll_votes (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_373a070e3c947c0f ON poll_votes
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX poll_id ON poll_votes (poll_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_373a070ea7c41d6f ON poll_votes
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX option_id ON poll_votes (option_id)
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
            ALTER TABLE projets DROP FOREIGN KEY FK_B454C1DB261A27D2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_b454c1db261a27d2 ON projets
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX technologie_id ON projets (technologie_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projets ADD CONSTRAINT FK_B454C1DB261A27D2 FOREIGN KEY (technologie_id) REFERENCES technologies (id) ON DELETE CASCADE
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
            DROP INDEX idx_5a10856433043433 ON vote
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idProjet ON vote (idProjet)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_5a108564159462a9 ON vote
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX FK_vote_votant ON vote (idVotant)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_5a10856491d7bb9f ON vote
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idEvaluation ON vote (idEvaluation)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_5a10856477d0dd19 ON vote
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idHackathon ON vote (idHackathon)
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
