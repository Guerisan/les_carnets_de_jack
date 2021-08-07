<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210807131015 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentary (id INT AUTO_INCREMENT NOT NULL, relation_id INT DEFAULT NULL, contribution_relation_id INT DEFAULT NULL, journal_entry_id INT DEFAULT NULL, author VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, date VARCHAR(255) DEFAULT NULL, INDEX IDX_1CAC12CA3256915B (relation_id), INDEX IDX_1CAC12CA2512148D (contribution_relation_id), INDEX IDX_1CAC12CA6A86E4FB (journal_entry_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contribution (id INT AUTO_INCREMENT NOT NULL, author VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, date VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, main_image VARCHAR(255) DEFAULT NULL, image_gallery_1 VARCHAR(255) DEFAULT NULL, image_gallery_2 VARCHAR(255) DEFAULT NULL, image_gallery_3 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, contribution_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_E01FBE6AFE5E5FBD (contribution_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal_entry (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, date VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, tags LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', illustration VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recit (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT NOT NULL, vue VARCHAR(255) NOT NULL, date VARCHAR(255) DEFAULT NULL, illustration VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE source (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, adress LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE source_recit (source_id INT NOT NULL, recit_id INT NOT NULL, INDEX IDX_7906880E953C1C61 (source_id), INDEX IDX_7906880E35C669E7 (recit_id), PRIMARY KEY(source_id, recit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, presentation LONGTEXT DEFAULT NULL, profile_pic VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CA3256915B FOREIGN KEY (relation_id) REFERENCES recit (id)');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CA2512148D FOREIGN KEY (contribution_relation_id) REFERENCES contribution (id)');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CA6A86E4FB FOREIGN KEY (journal_entry_id) REFERENCES journal_entry (id)');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AFE5E5FBD FOREIGN KEY (contribution_id) REFERENCES contribution (id)');
        $this->addSql('ALTER TABLE source_recit ADD CONSTRAINT FK_7906880E953C1C61 FOREIGN KEY (source_id) REFERENCES source (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE source_recit ADD CONSTRAINT FK_7906880E35C669E7 FOREIGN KEY (recit_id) REFERENCES recit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CA2512148D');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6AFE5E5FBD');
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CA6A86E4FB');
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CA3256915B');
        $this->addSql('ALTER TABLE source_recit DROP FOREIGN KEY FK_7906880E35C669E7');
        $this->addSql('ALTER TABLE source_recit DROP FOREIGN KEY FK_7906880E953C1C61');
        $this->addSql('DROP TABLE commentary');
        $this->addSql('DROP TABLE contribution');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE journal_entry');
        $this->addSql('DROP TABLE recit');
        $this->addSql('DROP TABLE source');
        $this->addSql('DROP TABLE source_recit');
        $this->addSql('DROP TABLE user');
    }
}
