<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250825072552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attachment (id INT AUTO_INCREMENT NOT NULL, type_piece VARCHAR(50) NOT NULL, nom_fichier VARCHAR(150) NOT NULL, hash VARCHAR(64) NOT NULL, url LONGTEXT NOT NULL, taille INT NOT NULL, verif_statut VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE audit_log (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(100) NOT NULL, entite VARCHAR(50) NOT NULL, avant LONGTEXT NOT NULL, apres LONGTEXT NOT NULL, ip VARCHAR(45) NOT NULL, user_agent VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE citizen_profile (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom VARCHAR(50) NOT NULL, prenoms VARCHAR(50) NOT NULL, date_de_naissance DATE NOT NULL, nin VARCHAR(20) NOT NULL, adresse VARCHAR(150) NOT NULL, commune VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_467217F1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE code_validation (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, code VARCHAR(10) NOT NULL, created_at DATETIME NOT NULL, expires_at DATETIME NOT NULL, is_used TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_3E02A6889D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE decision (id INT AUTO_INCREMENT NOT NULL, request_id INT NOT NULL, resultat VARCHAR(20) NOT NULL, motif LONGTEXT NOT NULL, valide_par VARCHAR(100) NOT NULL, valide_le DATETIME NOT NULL, INDEX IDX_84ACBE48427EB8A5 (request_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) NOT NULL, version VARCHAR(20) NOT NULL, url_pdf LONGTEXT NOT NULL, hash VARCHAR(64) NOT NULL, qr_payload LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, canal_to VARCHAR(100) NOT NULL, template VARCHAR(50) NOT NULL, payload LONGTEXT NOT NULL, statut VARCHAR(20) NOT NULL, horodatage DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, request_id INT DEFAULT NULL, mode VARCHAR(20) NOT NULL, reference VARCHAR(50) NOT NULL, montant NUMERIC(12, 2) NOT NULL, statut VARCHAR(20) NOT NULL, recu_url LONGTEXT NOT NULL, INDEX IDX_6D28840D427EB8A5 (request_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request (id INT AUTO_INCREMENT NOT NULL, attachment_id INT NOT NULL, document_id INT NOT NULL, task_id INT NOT NULL, payment_id INT NOT NULL, decision_id INT NOT NULL, ref VARCHAR(50) NOT NULL, statut VARCHAR(20) NOT NULL, centre VARCHAR(50) NOT NULL, priorite INT NOT NULL, montant NUMERIC(12, 2) NOT NULL, canal VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3B978F9F464E68B (attachment_id), INDEX IDX_3B978F9FC33F7837 (document_id), INDEX IDX_3B978F9F8DB60186 (task_id), INDEX IDX_3B978F9F4C3A3BB (payment_id), INDEX IDX_3B978F9FBDEE7539 (decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request_type (id INT AUTO_INCREMENT NOT NULL, request_id INT NOT NULL, code VARCHAR(50) NOT NULL, libelle VARCHAR(100) NOT NULL, schema_formulaire LONGTEXT NOT NULL, piece_requise LONGTEXT NOT NULL, delais_cible INT NOT NULL, workflow LONGTEXT NOT NULL, INDEX IDX_F37970D3427EB8A5 (request_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signature (id INT AUTO_INCREMENT NOT NULL, decision_id INT NOT NULL, type VARCHAR(20) NOT NULL, scelle VARCHAR(100) NOT NULL, horodatage DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_AE880141BDEE7539 (decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, etape VARCHAR(50) NOT NULL, due_date DATE NOT NULL, statut VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, hash_mdp VARCHAR(255) NOT NULL, statut VARCHAR(50) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, langue VARCHAR(255) DEFAULT NULL, two_faenabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, role_id INT NOT NULL, portee VARCHAR(50) NOT NULL, INDEX IDX_2DE8C6A3A76ED395 (user_id), INDEX IDX_2DE8C6A3D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE citizen_profile ADD CONSTRAINT FK_467217F1A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE code_validation ADD CONSTRAINT FK_3E02A6889D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE48427EB8A5 FOREIGN KEY (request_id) REFERENCES request (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D427EB8A5 FOREIGN KEY (request_id) REFERENCES request (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F464E68B FOREIGN KEY (attachment_id) REFERENCES attachment (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FC33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FBDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE request_type ADD CONSTRAINT FK_F37970D3427EB8A5 FOREIGN KEY (request_id) REFERENCES request (id)');
        $this->addSql('ALTER TABLE signature ADD CONSTRAINT FK_AE880141BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE citizen_profile DROP FOREIGN KEY FK_467217F1A76ED395');
        $this->addSql('ALTER TABLE code_validation DROP FOREIGN KEY FK_3E02A6889D86650F');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE48427EB8A5');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D427EB8A5');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9F464E68B');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FC33F7837');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9F8DB60186');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9F4C3A3BB');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FBDEE7539');
        $this->addSql('ALTER TABLE request_type DROP FOREIGN KEY FK_F37970D3427EB8A5');
        $this->addSql('ALTER TABLE signature DROP FOREIGN KEY FK_AE880141BDEE7539');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC');
        $this->addSql('DROP TABLE attachment');
        $this->addSql('DROP TABLE audit_log');
        $this->addSql('DROP TABLE citizen_profile');
        $this->addSql('DROP TABLE code_validation');
        $this->addSql('DROP TABLE decision');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE request');
        $this->addSql('DROP TABLE request_type');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE signature');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
