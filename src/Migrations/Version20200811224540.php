<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200811224540 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commentary CHANGE relation_id relation_id INT DEFAULT NULL, CHANGE contribution_relation_id contribution_relation_id INT DEFAULT NULL, CHANGE journal_entry_id journal_entry_id INT DEFAULT NULL, CHANGE date date VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE contribution CHANGE date date VARCHAR(255) DEFAULT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL, CHANGE main_image main_image VARCHAR(255) DEFAULT NULL, CHANGE image_gallery_1 image_gallery_1 VARCHAR(255) DEFAULT NULL, CHANGE image_gallery_2 image_gallery_2 VARCHAR(255) DEFAULT NULL, CHANGE image_gallery_3 image_gallery_3 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE images CHANGE contribution_id contribution_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_entry CHANGE tags tags LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE illustration illustration VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE recit CHANGE date date VARCHAR(255) DEFAULT NULL, CHANGE illustration illustration VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commentary CHANGE relation_id relation_id INT DEFAULT NULL, CHANGE contribution_relation_id contribution_relation_id INT DEFAULT NULL, CHANGE journal_entry_id journal_entry_id INT DEFAULT NULL, CHANGE date date VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE contribution CHANGE date date VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE status status VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE main_image main_image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE image_gallery_1 image_gallery_1 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE image_gallery_2 image_gallery_2 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE image_gallery_3 image_gallery_3 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE images CHANGE contribution_id contribution_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_entry CHANGE tags tags LONGTEXT CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', CHANGE illustration illustration VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE recit CHANGE date date VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE illustration illustration VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
