<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307184549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, nom_article VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_type_article (article_id INT NOT NULL, type_article_id INT NOT NULL, INDEX IDX_F95716027294869C (article_id), INDEX IDX_F95716026F9750B9 (type_article_id), PRIMARY KEY(article_id, type_article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compose (id INT AUTO_INCREMENT NOT NULL, id_article_id INT NOT NULL, id_liste_id INT NOT NULL, quantite INT NOT NULL, est_marque TINYINT(1) NOT NULL, INDEX IDX_AE4C1416D71E064B (id_article_id), INDEX IDX_AE4C1416B88FAD4D (id_liste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste (id INT AUTO_INCREMENT NOT NULL, nom_liste VARCHAR(255) NOT NULL, date_creation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste_utilisateur (liste_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_462BF5FE85441D8 (liste_id), INDEX IDX_462BF5FFB88E14F (utilisateur_id), PRIMARY KEY(liste_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_article (id INT AUTO_INCREMENT NOT NULL, nom_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_type_article ADD CONSTRAINT FK_F95716027294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_type_article ADD CONSTRAINT FK_F95716026F9750B9 FOREIGN KEY (type_article_id) REFERENCES type_article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compose ADD CONSTRAINT FK_AE4C1416D71E064B FOREIGN KEY (id_article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE compose ADD CONSTRAINT FK_AE4C1416B88FAD4D FOREIGN KEY (id_liste_id) REFERENCES liste (id)');
        $this->addSql('ALTER TABLE liste_utilisateur ADD CONSTRAINT FK_462BF5FE85441D8 FOREIGN KEY (liste_id) REFERENCES liste (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE liste_utilisateur ADD CONSTRAINT FK_462BF5FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_type_article DROP FOREIGN KEY FK_F95716027294869C');
        $this->addSql('ALTER TABLE article_type_article DROP FOREIGN KEY FK_F95716026F9750B9');
        $this->addSql('ALTER TABLE compose DROP FOREIGN KEY FK_AE4C1416D71E064B');
        $this->addSql('ALTER TABLE compose DROP FOREIGN KEY FK_AE4C1416B88FAD4D');
        $this->addSql('ALTER TABLE liste_utilisateur DROP FOREIGN KEY FK_462BF5FE85441D8');
        $this->addSql('ALTER TABLE liste_utilisateur DROP FOREIGN KEY FK_462BF5FFB88E14F');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_type_article');
        $this->addSql('DROP TABLE compose');
        $this->addSql('DROP TABLE liste');
        $this->addSql('DROP TABLE liste_utilisateur');
        $this->addSql('DROP TABLE type_article');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
